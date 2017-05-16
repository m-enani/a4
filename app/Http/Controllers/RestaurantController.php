<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Choice;
use App\Link;
use App\Rank;
use App\Voter;

class RestaurantController extends Controller
{

    # function used (with modification) from: http://itsolutionstuff.com/post/laravel-5-autocomplete-using-bootstrap-typeahead-js-example-with-demoexample.html
    public function autocomplete(Request $request)
    {

        $data = Category::select("name")->where("name","LIKE","{$request->input('query')}%")->get();
            return response()->json($data);
    }

    public function search(Request $request){

        session_start();

        # create random id to use for session id
        if(!$_SESSION){
            $random = md5(uniqid(mt_rand(), true));
            $_SESSION["newsession"] = $random;
        }

        // dump($_SESSION['newsession']);

        $rules = [
            'location' => 'required',
            'type' => 'nullable|exists:categories,name',
        ];

        $customMessages = [
            'exists' => 'Sorry, "'.$request->input('type').'" is not a type of restaurant that exists in our database.'
        ];

        $this->validate($request, $rules, $customMessages);

        # Checks if user entered a restaurant type
        if($request->input('type')){
            $type = (Category::select("search_term")->where("name",$request->input('type'))->get())->toArray();
            $type = $type[0]['search_term'];
        }
        else{
            $type = "";
        }

        $curl = curl_init();

        # check if location entry is already a zip code
        if (is_int($request->input('location'))) {
            $location = $request->input('location');
        }
        else {
            $location = str_replace(' ', '-',  $request->input('location'));
        }

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.yelp.com/v3/businesses/search?term=restaurant&limit=10&sort_by=rating&categories=".$type."&radius=".$request->input('radius')."&location='"
          .$location."'&price=".$request->input('price'),
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "authorization: Bearer s8lFVztLvdOloB0F7-z_XF8GaSMIRHOGgtn4loszrqu1CJC1-nj9I_dfMwbXtENJlPw2C8l4ChPAk-VZ4s3GJUeVwTKg2DRMkLn2mRml0-qTA_O_KvXXEB1_LHASWXYx",
            "cache-control: no-cache",
            "postman-token: c6026ff4-fc52-5838-229a-45847a5ab3bc"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        $restaurants = json_decode($response, true);

        curl_close($curl);

        $filteredRestaurants = [];

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
            $i=0;
            foreach($restaurants['businesses'] as $restaurant){
                foreach($restaurant['categories'] as $title){
                    $titles [] =  $title['title'];
                }
                $filteredRestaurants[] = ['id' => $i,
                    'name' => $restaurant['name'],
                    'yelpType' => implode(", ",$titles),
                    'image' => $restaurant['image_url'],
                    'address' => implode(" ",$restaurant['location']['display_address']),
                    'phone' => $restaurant['display_phone'],
                    'price' => $restaurant['price'],
                    'rating' => $restaurant['rating'],
                    'url' => $restaurant['url'],
                ];

                unset($titles);
                $i++;
            }
        }

        if (isset($_SESSION['choices'])) {
            $userChoices = $_SESSION['choices'];
        }
        else {
            $userChoices = [];
        }
        return view('restaurants.results',[
            'filteredRestaurants' => $filteredRestaurants,
            'type' => $request->input('type'),
            'location' => ucwords($request->input('location')),
            'choices' => $userChoices,
            'exists' => false,
        ]);
    }

    public function add(Request $request){

        $id = $request->input('id');
        $type = $request->input('yelpType'.$id);

        session_start();
        $filteredRestaurants = $_SESSION['restaurant'];
        $location = $_SESSION['location'];

        $restaurant = new Choice();
        $restaurant->user_id = $_SESSION["newsession"];
        $restaurant->name = $request->input('name'.$id);
        $restaurant->type = $type;
        $restaurant->address= $request->input('address'.$id) ? : 'NA';
        $restaurant->phone = $request->input('phone'.$id) ? : 'NA';
        $restaurant->price = $request->input('price'.$id) ? : 'NA';
        $restaurant->rating = $request->input('rating'.$id) ? : 'NA';
        $restaurant->image_url = $request->input('image'.$id) ? : 'NA';
        $restaurant->more_info = $request->input('url'.$id) ? : 'NA';
        $restaurant->save();

        # Get current users choices
        $userChoices = (Choice::where("user_id", '=', $_SESSION["newsession"])->get())->toArray();

        $anchor = $request->input('name'.$id);

        return view('restaurants.results',[
            'anchor' => $anchor,
            'filteredRestaurants' => $filteredRestaurants,
            'type' => ucwords($type),
            'location' => $location,
            'choices' => $userChoices,
            'exists' => false,
        ]);
    }

    public function remove(Request $request){

        session_start();
        $filteredRestaurants = $_SESSION['restaurant'];
        $location = $_SESSION['location'];
        $id = $request['id'];
        $type = $request->input('yelpType'.$id);

        # Find restaurant to remove
        $deleteChoice = Choice::where("user_id", '=', $_SESSION["newsession"])->where("name", $request->input('name'.$id))->first();

        # Check if associated with rank (pivot table)
        $hasRank = $deleteChoice->toArray();

        if (Rank::where('id', $hasRank['id'])) {
            $deleteChoice->ranks()->detach();
        }

        $deleteChoice->delete();

        # Get current users choices
        $userChoices = (Choice::where("user_id", '=', $_SESSION["newsession"])->get())->toArray();

        return view('restaurants.results',[
            'filteredRestaurants' => $filteredRestaurants,
            'type' => $type,
            'location' => $location,
            'choices' => $userChoices,
            'exists' => false,
        ]);

    }

    public function removechoice(Request $request){

        session_start();
        $filteredRestaurants = $_SESSION['restaurant'];
        $location = $_SESSION['location'];
        $id = $request['id'];
        $type = $request->input('yelpType'.$id);

        # Find restaurant to remove
        $deleteChoice = Choice::find($request->input('id'));

        $hasRank = $deleteChoice->toArray();

        # Check if associated with rank (pivot table)
        if (Rank::where('id', $hasRank['id'])) {
            $deleteChoice->ranks()->detach();
        }

        $deleteChoice->delete();

        # Get current users choices
        $userChoices = (Choice::where("user_id", '=', $_SESSION["newsession"])->get())->toArray();

        return view('restaurants.results',[
            'filteredRestaurants' => $filteredRestaurants,
            'type' => $type,
            'location' => $location,
            'choices' => $userChoices,
            'exists' => false,
        ]);
    }

    public function vote(Request $request){

        # Get values from url
        $parameters= implode( parse_url($_SERVER['QUERY_STRING']));

        $parameters = explode("&", $parameters);
        $invitation = $parameters[0];
        $time = $parameters[1];

        # check if valid integer
        if(!is_int($time)){
            $time = 60;
        }
        else if($time <= 0){
            $time = 60;
        }

        # save created link if not already in table

        $found = Link::where("user_id", $invitation)->first();

        if (is_null($found)){
            $link = new Link();
            $link->user_id = $invitation;
            $link->expires = $time;
            $link->save();
        }

        # Get vote initiater's choices
        $userChoices = (Choice::where("user_id", '=', $invitation)->get())->toArray();
        $ranks = Rank::all()->toArray();

        return view('restaurants.vote',[
            'choices' => $userChoices,
            'ranks' => $ranks,
            'invitation' => $userChoices[0]['user_id'],
            'time' => $time,
        ]);
    }


    public function tally(Request $request){

        // dd($request->all());

        # check if the person has already voted
        $voted = Voter::where('name',$request->input('name'))->where('invitation', $request->input('invitation'))->first();

        if (!isset($voted)) {

            $count = 0;

            $entries[] = $request->all() ;

            foreach($entries as $entry){

                $choice = Choice::where('id', $entry['id'.$count])->first();
                $rank = Rank::where('description',  $entry['rank'.$count])->first();

                $choice->ranks()->save($rank);

                $count +=1;
            }

            $voter= new Voter();
            $voter->name = $request->input('name');
            $voter->invitation = $request->input('invitation');
            $voter->save();

            $repeat = false;
        }
        else{
            $repeat = true;
        }

        # get expiration time
        $link = Link::where('user_id', $request->input('invitation'))->get()->toArray();

        $expires = date_create($link[0]['created_at']);

        return view('restaurants.entered',[
            'invitation' => $request->input('invitation'),
            'expires' => $expires,
        ]);
    }

    // coming soon!
    public function winner(Request $request){

        $invitation = parse_url($_SERVER['QUERY_STRING']);

    }
}
