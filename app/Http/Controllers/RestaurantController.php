<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Choice;

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
        Choice::where("user_id", '=', $_SESSION["newsession"])->where("name", $request->input('name'.$id))->delete();
        // (Choice::where("user_id", '=', $_SESSION["newsession"])->where("name", $request->input('name'.$id))->get())->toArray();

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
        Choice::find($request->input('id'))->delete();

        # Get current users choices
        $userChoices = (Choice::where("user_id", '=', $_SESSION["newsession"])->get())->toArray();

        return view('restaurants.results',[
            'filteredRestaurants' => $filteredRestaurants,
            'type' => $type,
            'location' => $location,
            'choices' => $userChoices,
            'exists' => false,
        ]);;
    }

    public function share(Request $request){

        // $emails = explode(",", $request->input('emails'));
        //
        // foreach ($emails as $email){
        //     $to = $email;
        //     $subject = $request->input('name')." would like you to vote for LunchOUT!";
        //     $txt = "Time to get your vote on! Have your say on were to go to lunch by clicking on the link below. Happy LunchOUT!";
        //     $headers = "From: " .$request->input('senderEmail'). "\r\n";
        //
        //     mail($to,$subject,$txt,$headers);
        // }
        //

        return view('restaurants.share');
    }

    public function vote(Request $request){
        return view('restaurants.vote');
    }

}
