<?php

use Layla\API;

class Admin_Account_Form {

	public static function add($form)
	{
		// Get Roles and put it in a nice array for the dropdown
		$roles = array('' => '') + model_array_pluck(API::get(array('role', 'all'))->get('results'), function($role)
		{
			return $role->lang->name;
		}, 'id');

		// Get Languages and put it in a nice array for the dropdown
		$languages = model_array_pluck(API::get(array('language', 'all'))->get('results'), function($language)
		{
			return $language->name;
		}, 'id');

		$form->text('name',  __('admin::account.add.form.name'), Input::old('name'));
		$form->text('email', __('admin::account.add.form.email'), Input::old('email'));
		$form->password('password', __('admin::account.add.form.password'));
		$form->multiple('roles[]', __('admin::account.add.form.roles'), $roles, Input::old('roles'));
		$form->dropdown('language_id', __('admin::account.add.form.language'), $languages, Input::old('language_id'));

		$form->actions(function($form)
		{
			$form->submit(__('admin::account.add.buttons.add'), 'primary');
		});
	}

	public static function edit($form, $id)
	{
		// Get the Account
		$response = API::get(array('account', $id));

		// Handle response codes other than 200 OK
		if( ! $response->success)
		{
			return Event::first($response->code);
		}

		// The response body is the Account
		$account = $response->get();

		// Get Roles and put it in a nice array for the dropdown
		$roles = array('' => '') + model_array_pluck(API::get(array('role', 'all'))->get('results'), function($role)
		{
			return $role->lang->name;
		}, 'id');

		// Get the Roles that belong to a User and put it in a nice array for the dropdown
		$active_roles = array();
		if(isset($account->roles))
		{ 
			$active_roles = model_array_pluck($account->roles, 'id', '');
		}

		// Get Languages and put it in a nice array for the dropdown
		$languages = model_array_pluck(API::get(array('language', 'all'))->get('results'), function($language)
		{
			return $language->name;
		}, 'id');

		$form->text('name',  __('admin::account.edit.form.name'), Input::old('name', $account->name));
		$form->text('email', __('admin::account.edit.form.email'), Input::old('email', $account->email));
		$form->password('password', __('admin::account.edit.form.password'));
		$form->multiple('roles[]', __('admin::account.edit.form.roles'), $roles, Input::old('roles', $active_roles));
		$form->dropdown('language_id', __('admin::account.edit.form.language'), $languages, Input::old('language_id', $account->language->id));

		$form->actions(function($form)
		{
			$form->submit(__('admin::account.edit.buttons.edit'), 'primary');
		});
	}

	public static function delete($form, $id)
	{
		$form->actions(function($form)
		{
			$form->submit(__('admin::account.delete.buttons.delete'), 'primary');
			$form->button(prefix('admin').'account', __('admin::account.delete.buttons.cancel'));
		});
	}

}