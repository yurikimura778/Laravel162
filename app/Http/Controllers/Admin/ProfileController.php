<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// 以下を追記することでProfile Modelが扱えるようになる
use App\Profile;

// 以下を追記
use App\ProfileHistory;

// 以下を追記
use Carbon\Carbon;

class ProfileController extends Controller
{
    //
     public function add()
  {
      return view('admin.profile.create');
  }

  public function create(Request $request)
  {
      
  // 以下を追記
      // Varidationを行う
      $this->validate($request, Profile::$rules);
    
    $profile = new Profile;
    $form = $request->all();
    

      // フォームから送信されてきた_tokenを削除する
      unset($form['_token']);
      // フォームから送信されてきたimageを削除する
  
    
    
    $profile->name=$form['name'];
    $profile->gender=$form['gender'];
    $profile->hobby=$form['hobby'];
    $profile->introduction=$form['introduction'];
    $profile->save();
    
    
      return redirect('admin/profile/create');
  }

// 以下を追記
  public function index(Request $request)
  {
      $cond_title = $request->cond_title;
      if ($cond_title != '') {
          // 検索されたら検索結果を取得する
          $posts = Profile::where('title', $cond_title)->get();
      } else {
          // それ以外はすべてのニュースを取得する
          $posts = Profile::all();
      }
      return view('admin.profile.index', ['posts' => $posts, 'cond_title' => $cond_title]);
  }

  public function edit(Request $request)
  {
      // Profile Modelからデータを取得する
      $profile = Profile::find($request->id);
      if (empty($profile)) {
        abort(404);    
      }
      return view('admin.profile.edit', ['profile_form' => $profile]);
  }
  
public function update(Request $request)
  {
      // Validationをかける
      $this->validate($request, Profile::$rules);
      // Profile Modelからデータを取得する
      $profile = Profile::find($request->id);
      // 送信されてきたフォームデータを格納する
      $profile_form = $request->all();
      
 
      unset($profile_form['_token']);
      unset($profile_form['remove']);

      // 該当するデータを上書きして保存する
      $profile->fill($profile_form)->save();
      
       // 以下を追記
        $profile_history = new ProfileHistory;
        $profile_history->profile_id = $profile->id;
        $profile_history->edited_at = Carbon::now();
        $profile_history->save();
      

      return redirect('admin/profile');
  }


}
