<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Validator;

class EmailTemplateController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $emailtemplates = EmailTemplate::all()->sortByDesc("id");
        return view('backend.administration.email_template.list', compact('emailtemplates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        if (!$request->ajax()) {
            return view('backend.administration.email_template.create');
        } else {
            return view('backend.administration.email_template.modal.create');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'    => '',
            'subject' => 'required',
            'body'    => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('email_templates.create')
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        $emailtemplate          = new EmailTemplate();
        $emailtemplate->name    = $request->input('name');
        $emailtemplate->subject = $request->input('subject');
        $emailtemplate->body    = $request->input('body');

        $emailtemplate->save();

        if (!$request->ajax()) {
            return redirect()->route('email_templates.create')->with('success', _lang('Saved Sucessfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'store', 'message' => _lang('Saved Sucessfully'), 'data' => $emailtemplate]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {
        $emailtemplate = EmailTemplate::find($id);

        if (!$request->ajax()) {
            return view('backend.administration.email_template.view', compact('emailtemplate', 'id'));
        } else {
            return view('backend.administration.email_template.modal.view', compact('emailtemplate', 'id'));
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id) {
        $vars = array(
            'welcome_email'       => '{name} {email} {phone}',
            'order_placed'        => '{name} {order_id} {delivery_status} {payment_method} {payment_status}',
            'order_processing'    => '{name} {order_id} {delivery_status} {payment_method} {payment_status}',
            'order_completed'     => '{name} {order_id} {delivery_status} {payment_method} {payment_status}',
            'order_canceled'      => '{name} {order_id} {delivery_status} {payment_method} {payment_status}',
            'assign_delivery_boy' => '{name} {order_id} {delivery_status} {payment_method} {payment_status} {customer_name} {customer_email} {customer_phone} {shipping_address}',
        );
        $emailtemplate = EmailTemplate::find($id);

        if (!$request->ajax()) {
            return view('backend.administration.email_template.edit', compact('emailtemplate', 'vars', 'id'));
        } else {
            return view('backend.administration.email_template.modal.edit', compact('emailtemplate', 'vars', 'id'));
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'name'    => '',
            'subject' => 'required',
            'body'    => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('email_templates.edit', $id)
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        $emailtemplate          = EmailTemplate::find($id);
        $emailtemplate->subject = $request->input('subject');
        $emailtemplate->body    = $request->input('body');

        $emailtemplate->save();

        if (!$request->ajax()) {
            return redirect()->route('email_templates.index')->with('success', _lang('Updated Sucessfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'update', 'message' => _lang('Updated Sucessfully'), 'data' => $emailtemplate]);
        }
    }
}
