<?php

namespace App\Http\Controllers;

use App\Models\Shortlink;
use Illuminate\Http\Request;
use App\Http\Requests\ShortlinkRequest;

class ShortlinkController extends Controller
{
    public function index()
    {
        if (auth()->user()) {
            $data = Shortlink::where('user_id', auth()->id())->with('user')->orderBy('created_at', 'desc')->get();
            return (view('dashboard', compact('data')));
        }
    }
    public function short(ShortlinkRequest $request)
    {
        if ($request->original_url) {
            if (auth()->user()) {
                //dd(auth()->user()->links());
                $new_url = auth()->user()->links()->create([
                    'original_url' => $request->original_url
                ]);
            } else {
                $new_url = Shortlink::create(['original_url' => $request->original_url]);
            }
            if ($new_url) {
                $short_url = base_convert((($new_url->id) * 1024), 10, 36);
                $new_url->update(['short_url' => $short_url]);
                return redirect()->back()->with('success_url', 'Short url : <a class="text-red-400 hover:text-red-900" href="' . url($short_url) . '" target="_blank">' . url($short_url) . '</a>');
            }
        }
        return back();
    }

    public function show($code)
    {
        $short_url = Shortlink::where('short_url', $code)->first();
        if ($short_url->user_id==null) {
            $threeDaysInSeconds = 3 * 24 * 60 * 60; //days for exist
            $date_url = strtotime($short_url->created_at);
            $date_current = strtotime(date('Y-m-d H:i:s'));
            if (($date_current - $date_url) > $threeDaysInSeconds) {
                $short_url->delete();
                return redirect()->to(url('/'));
            }else{
                $short_url->increment('visits');
                return redirect()->to(url($short_url->original_url));
            }
        }else{
            if ($short_url) {
                $short_url->increment('visits');
                return redirect()->to(url($short_url->original_url));
            }
        }
        return redirect()->to(url('/'));
    }
}
