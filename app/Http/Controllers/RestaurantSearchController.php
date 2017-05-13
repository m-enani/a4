<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class RestaurantSearchController extends Controller
{

    # function used (with modification) from: http://itsolutionstuff.com/post/laravel-5-autocomplete-using-bootstrap-typeahead-js-example-with-demoexample.html
    public function autocomplete(Request $request)
    {

        $data = Category::select("name")->where("name","LIKE","{$request->input('query')}%")->get();
        if($data->count()){
            return response()->json($data);
        }
        else{
            return  response()->array('No Results Found!');
        }
    }

    public function search(Request $request){


        $rules = [
            'location' => 'required',
            'type' => 'exists:categories,name',
        ];

        $customMessages = [
            'exists' => 'Sorry, "'.$request->input('type').'" is not a type of restaurant that exists in our database.'
        ];

        $this->validate($request, $rules, $customMessages);

        $type= (Category::select("search_term")->where("name",$request->input('type'))->get())->toArray();

        $curl = curl_init();

        // FOR TESTING ONLY!!!! REMOVE BEFORE PULLING TO PROD!
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.yelp.com/v3/businesses/search?term=restaurant&limit=5&sort_by=rating&categories=".$type[0]['search_term']."&location=".$request->input('location'),
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
                $filteredRestaurants[] = ['id'=> $i,
                    'name'=>$restaurant['name'],
                    'image'=>$restaurant['image_url']];
                $i++;
            }
        }

        // # sort the array by restaurant name
        // $filteredRestaurants = array_values(array_sort($filteredRestaurants, function ($value) {
        //     return $value;
        // }));

        // foreach($filteredRestaurants as $restaurant){
        //     print_r($restaurant['image']);
        // }

        return view('restaurants.search',[
            'filteredRestaurants' => $filteredRestaurants
        ]);
    }
}
