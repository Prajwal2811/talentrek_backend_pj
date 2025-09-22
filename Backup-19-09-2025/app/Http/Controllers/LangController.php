<?php

  

namespace App\Http\Controllers;

  
use App\Models\Language;
use Illuminate\Http\Request;

use App;

  

class LangController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

    */

    public function index()

    {

        return view('lang');

    }

  

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

    */

    public function change(Request $request)

    {

        App::setLocale($request->lang);

        session()->put('locale', $request->lang);

  

        return redirect()->back();

    }

    public function showForm()
    {
        $lang = session('lang', 'english'); // default is English

        $translations = TalentrekLanguage::all()->pluck($lang, 'code');

        return view('form', compact('translations', 'lang'));
    }

    public function changeLanguage(Request $request)
    {
        $request->validate([
            'lang' => 'required|in:english,arabic',
        ]);

        session(['lang' => $request->lang]);

        return redirect()->back(); // Go back to the form page
    }

}