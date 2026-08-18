<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ContactController extends Controller
{
    public function view()
    {
        $contacts = User::whereNotNull('directdial')->orWhereNotNull('extension')->get();


          $heads = ['Name', 'Extension', 'Direct Dial'];


        $data = [];

        foreach ($contacts as $contact) {

            
            $data[] = [
                $contact->name,
                $contact->extension,
                $contact->directdial
               
            ];

            
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'lengthChange' => false,
            'paging' => false,
            'info' => true,
            'columns' => [null, null, null],
            'buttons' =>  [
                 
                ]

        ];


        return view('contacts.contactlist', compact('heads', 'config'));

    }


}
