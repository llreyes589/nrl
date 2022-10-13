<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::all();
        return view('announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('announcements.form');
    }

    public function store()
    {
        $validated = \request()->validate(
            ['title' => 'required', 'body' => 'required', 'announcement_attachment' => 'required']
        );
        if (\request()->file('announcement_attachment')) {

            $attachment = \request()->file('announcement_attachment')->store('announcement_attachment');
        } else {
            $attachment = null;
        }

        request()->merge(['attachment' => $attachment]);
        Announcement::create(\request()->except('_token'));
        return redirect()->route('announcements.index')->with(['message' => "Announcement Saved.", 'classname' => 'alert-success']);
    }

    public function edit($id)
    {
        $announcement = Announcement::find($id);
        return view('announcements.form', compact('announcement'));
    }

    public function update($id)
    {
        $announcement = Announcement::find($id);
        $validated = \request()->validate(
            ['title' => 'required', 'body' => 'required',]
        );
        if (\request()->file('announcement_attachment')) {

            $attachment = \request()->file('announcement_attachment')->store('announcement_attachment');
        } else {
            $attachment = $announcement->attachment;
        }

        request()->merge(['attachment' => $attachment]);
        $announcement->update(\request()->except('_token'));
        return redirect()->route('announcements.index')->with(['message' => "Announcement Updated.", 'classname' => 'alert-success']);
    }
    public function changeStatus($id)
    {
        $announcement = Announcement::find($id);
        $announcement->update(['publish' => $announcement->publish === 1 ? 0 : 1]);
        return redirect()->back()->with(['message' => "Announcement Updated.", 'classname' => 'alert-success']);
    }
}
