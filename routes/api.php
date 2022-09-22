<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/news.get', 'NewsController@index');
Route::get('/news.get.id', 'NewsController@getid');
Route::get('/news.categories.get', 'NewsController@getcategories');

Route::get('/articles.get', 'ArticlesController@index');
Route::get('/articles.get.id', 'ArticlesController@getid');
Route::get('/articles.categories.get', 'ArticlesController@getcategories');
Route::get('/articles.regpage.get', 'ArticlesController@regpage');

Route::get('/documents.get', 'DocumentsController@index');
Route::get('/documents.get_file', 'DocumentsController@get_file');
Route::get('/documents.categories.get', 'DocumentsController@getcategories');

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', 'Auth\LoginController@logout');

    Route::get('/user', 'Auth\UserController@current');

    Route::patch('settings/profile', 'Settings\ProfileController@update');
    Route::patch('settings/password', 'Settings\PasswordController@update');

    //Statement
    Route::get('/statement.my.get', 'Statement@index');
    Route::get('/statement.infodata_form.get', 'Statement@infodata_form');
    Route::get('/statement.my_form.get', 'Statement@my_form');
    Route::get('/statement.my_form_status.get', 'Statement@my_form_status');
    Route::post('/statement.step1.sending', 'Statement@step2');
    Route::post('/statement.my_files_add.post', 'Statement@add_files');
    Route::get('/statement.file_identification_document.get', 'Statement@get_file_identification_document');
    Route::get('/statement.get_file_document_on_education.get', 'Statement@get_file_document_on_education');
    
    Route::post('/statement.step1_edit_save.post', 'Statement@step1_edit_save');
    Route::get('/statement.get_specializations_info.get', 'Statement@get_specializations_info');
    Route::get('/statement.get_specialization_info.get', 'Statement@get_specialization_info');
    Route::post('/statement.application_step2.post', 'Statement@application_step2');
    Route::get('/application.file_statement_document.get', 'Statement@get_file_statement_document');
    Route::get('/application.my.info', 'Statement@get_application_my_info');
    Route::post('/statement.application_step2_edit.post', 'Statement@application_step2_edit');
    Route::post('/application.delete', 'Statement@application_destroy');

    //Admin
    Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
        //Access
    	Route::get('/access.get', 'Access@index');
    	Route::post('/access.permission.get', 'Access@get_info_permissions');
    	Route::post('/access.permission.add', 'Access@add_permission');
    	Route::post('/access.permission.save', 'Access@save_permission');
        Route::post('/access.permission.delete', 'Access@permission_destroy');
        Route::post('/access.role.add', 'Access@add_role');
        Route::post('/access.role.edit', 'Access@save_role');
        Route::post('/access.role.get', 'Access@get_info_role');
        Route::post('/access.role.delete', 'Access@role_destroy');
        Route::get('/access.role.getlist', 'Access@get_roles_list');
        Route::post('/access.addpermissions', [Access::class, 'AddPermissions']);
        Route::post('/access.addroles', [Access::class, 'AddRoles']);
        
        //Users
        Route::post('/users.add', 'Users@add');
        Route::post('/users.save', 'Users@save');
        Route::get('/users.get', 'Users@index');
        Route::post('/users.delete', 'Users@destroy');

        //News
        Route::post('/news.add', 'NewsController@add');
        Route::get('/news.get', 'NewsController@index');
        Route::get('/news.get.id', 'NewsController@getid');
        Route::post('/news.edit', 'NewsController@edit');
        Route::post('/news.delete', 'NewsController@destroy');
        Route::post('/news.editor.upload.images', 'NewsController@editor_upload_img');
        Route::post('/news.editor.fetchUrl', 'NewsController@editor_fetchUrl');
        Route::post('/news.editor.upload.file', 'NewsController@editor_upload_file');

        Route::get('/news.categories.get', 'NewsController@categories__get');
        Route::post('/news.categories.add', 'NewsController@categories__add');
        Route::post('/news.categories.save', 'NewsController@categories__save');
        Route::post('/news.categories.delete', 'NewsController@categories__delete');

        //Articles
        Route::post('/articles.add', 'ArticlesController@add');
        Route::get('/articles.get', 'ArticlesController@index');
        Route::get('/articles.get.id', 'ArticlesController@getid');
        Route::post('/articles.edit', 'ArticlesController@edit');
        Route::post('/articles.delete', 'ArticlesController@destroy');
        Route::post('/articles.editor.upload.images', 'ArticlesController@editor_upload_img');
        Route::post('/articles.editor.fetchUrl', 'ArticlesController@editor_fetchUrl');
        Route::post('/articles.editor.upload.file', 'ArticlesController@editor_upload_file');

        Route::get('/articles.categories.get', 'ArticlesController@categories__get');
        Route::post('/articles.categories.add', 'ArticlesController@categories__add');
        Route::post('/articles.categories.save', 'ArticlesController@categories__save');
        Route::post('/articles.categories.delete', 'ArticlesController@categories__delete');

        //Documents
        Route::post('/documents.add', 'DocumentsController@add');
        Route::get('/documents.get', 'DocumentsController@index');
        Route::get('/documents.get_file', 'DocumentsController@get_file');
        Route::post('/documents.delete', 'DocumentsController@destroy');
        Route::get('/documents.categories.get', 'DocumentsController@categories__get');
        Route::post('/documents.categories.add', 'DocumentsController@categories__add');
        Route::post('/documents.categories.save', 'DocumentsController@categories__save');
        Route::post('/documents.categories.delete', 'DocumentsController@categories__delete');


        //University information
        //
        Route::get('/information.education_levels.get', 'Information@education_levels__get');
        Route::post('/information.education_levels.add', 'Information@education_levels__add');
        Route::post('/information.education_levels.save', 'Information@education_levels__save');
        Route::post('/information.education_levels.delete', 'Information@education_levels__delete');

        Route::get('/information.faculties.get', 'Information@faculties__get');
        Route::get('/information.faculty.getlist', 'Information@get_faculties_list');
        Route::post('/information.faculties.add', 'Information@faculties__add');
        Route::post('/information.faculties.save', 'Information@faculties__save');
        Route::post('/information.faculties.delete', 'Information@faculties__delete');

        Route::get('/information.use.get', 'Information@use__get');
        Route::get('/information.use.get_use_list', 'Information@get_use_list');
        Route::post('/information.use.add', 'Information@use__add');
        Route::post('/information.use.save', 'Information@use__save');
        Route::post('/information.use.delete', 'Information@use__delete');

        Route::get('/information.identification_documents_name.get', 'Information@identification_documents_name__get');
        Route::post('/information.identification_documents_name.add', 'Information@identification_documents_name__add');
        Route::post('/information.identification_documents_name.save', 'Information@identification_documents_name__save');
        Route::post('/information.identification_documents_name.delete', 'Information@identification_documents_name__delete');

        Route::get('/information.document_on_education_name.get', 'Information@document_on_education_name__get');
        Route::post('/information.document_on_education_name.add', 'Information@document_on_education_name__add');
        Route::post('/information.document_on_education_name.save', 'Information@document_on_education_name__save');
        Route::post('/information.document_on_education_name.delete', 'Information@document_on_education_name__delete');

        Route::get('/information.additional_marks.get', 'Information@additional_marks__get');
        Route::post('/information.additional_marks.add', 'Information@additional_marks__add');
        Route::post('/information.additional_marks.save', 'Information@additional_marks__save');
        Route::post('/information.additional_marks.delete', 'Information@additional_marks__delete');

        Route::get('/information.stage_of_education.get', 'Information@stage_of_education__get');
        Route::post('/information.stage_of_education.add', 'Information@stage_of_education__add');
        Route::post('/information.stage_of_education.save', 'Information@stage_of_education__save');
        Route::post('/information.stage_of_education.delete', 'Information@stage_of_education__delete');

        //Specialties
        //
        Route::get('/specialties.get', 'Specialties@index');
        Route::get('/specialties.stage_of_education', 'Information@get_stage_of_education_list');
        Route::post('/specialties.add', 'Specialties@add');
        Route::post('/specialties.save', 'Specialties@update');
        Route::post('/specialties.delete', 'Specialties@destroy');

        //Applications
        //
        Route::get('/applications.get', 'Applications@index');
        Route::post('/applications.status.edit', 'Applications@status_edit');
        Route::get('/applications.file_statement_document.get', 'Applications@get_file_statement_document');
        Route::get('/applications.getinfo', 'Applications@getinfo');
        Route::post('/applications.delete', 'Applications@destroy');

        Route::get('/applications.file_identification_document.get', 'Applications@get_file_identification_document');
        Route::get('/applications.get_file_document_on_education.get', 'Applications@get_file_document_on_education');





    });
});

Route::group(['middleware' => 'guest:api'], function () {
    Route::post('login', 'Auth\LoginController@login');
    Route::post('register', 'Auth\RegisterController@register');

    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');

    Route::post('email/verify/{user}', 'Auth\VerificationController@verify')->name('verification.verify');
    Route::post('email/resend', 'Auth\VerificationController@resend');

    Route::post('oauth/{driver}', 'Auth\OAuthController@redirectToProvider');
    Route::get('oauth/{driver}/callback', 'Auth\OAuthController@handleProviderCallback')->name('oauth.callback');

});