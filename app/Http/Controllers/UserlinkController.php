<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Shortlink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserlinkController extends Controller
{
    public function index()
    {
        $links = User::with('links')->get();
        return view('admins.userlink', compact('links'));
    }
    public function datalink()
    {
        $links = Shortlink::with('user')->get();
        $links = $links->map(function ($link) {
            $link->user_name = $link->user ? $link->user->name : '';
            return $link;
        });
        return view('admins.linkmanagement', compact('links'));
    }
    public function deleteLinks(Request $request)
    {
        $linkIds = $request->input('linkIds', []);

        foreach ($linkIds as $linkId) {
            Shortlink::where('id', $linkId)->delete();
        }

        return response()->json(['success' => true]);
    }
}
