<?php
use Illuminate\Support\Facades\Route;

use App\Models\User;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
    Rercherche = project_teacher.all.show
*/


Route::get('/', function () {

     if (Auth::check()) {
        return redirect()->route('home');
    } else {
        return view('auth.login');
    }

})->name('accueil');




Route::get('/create-admin', function () {
    $admin = User::firstOrCreate([
        'email' => 'admin@gmail.com',
    ], [
        'name' => 'admin',
        'first_name' => 'admin',
        'phone' => '77000000',
        'role' => 0,
        'first_connection' => 0,
        'password' => bcrypt('admin123'),
    ]);

    return redirect(route('accueil'));
});




/*Auth::routes();*/

Auth::routes([

  'register' => false, // Register Routes...

  'reset' => false, // Reset Password Routes...

  'verify' => false, // Email Verification Routes...

]);



Route::middleware('adminAccess')->group(function(){

                        ///////////// ADMINISTRATEUR HOME/////////////

            Route::get('/affichage_projects_proceed', [App\Http\Controllers\HomeController::class, 'projects_proceed'])->name('show.projects.proceed');
            Route::get('/affichage_projects_finish', [App\Http\Controllers\HomeController::class, 'projects_finish'])->name('show.projects_finish');
            Route::get('/affichage_projects_without_groups', [App\Http\Controllers\HomeController::class, 'projects_without_groups'])->name('show.without.groups');


        ////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////// User //////////////////////////////////// 
        ////////////////////////////////////////////////////////////////////////////////


        Route::get('/user_index', [App\Http\Controllers\UserController::class, 'index'])->name('users');

        Route::match(['get','post'],'/user_create', [App\Http\Controllers\UserController::class, 'create'])->name('user.create');

        Route::match(['get','post'],'/user_edit/{id}', [App\Http\Controllers\UserController::class, 'edit'])->name('user.edit');

        Route::match(['get','post'],'/user_projects_show/{id}', [App\Http\Controllers\UserController::class, 'show'])->name('user.projects.show');

        Route::get('/user_delete/{id}', [App\Http\Controllers\UserController::class, 'delete'])->name('user.delete');

        Route::match(['get','post'],'/user_search', [App\Http\Controllers\UserController::class, 'user_search'])->name('user.search');

         Route::match(['get','post'],'/user_students', [App\Http\Controllers\UserController::class, 'user_students'])->name('user.students');

          Route::match(['get','post'],'/user_teachers', [App\Http\Controllers\UserController::class, 'user_teachers'])->name('user.teachers');

           Route::match(['get','post'],'/user_group/{group_id}', [App\Http\Controllers\UserController::class, 'show_group'])->name('user.group');


        ////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////// Group //////////////////////////////////// 
        ////////////////////////////////////////////////////////////////////////////////

        Route::get('/group_index', [App\Http\Controllers\GroupController::class, 'index'])->name('groups');

        Route::match(['get','post'],'/group_create', [App\Http\Controllers\GroupController::class, 'create'])->name('group.create');


        Route::match(['get','post'],'/group_edit/{id}', [App\Http\Controllers\GroupController::class, 'edit'])->name('group.edit');

        Route::match(['get','post'],'/group_show_project/{id}', [App\Http\Controllers\GroupController::class, 'show_project'])->name('group.show.project');

        Route::match(['get','post'],'/group_show_teacher/{id}', [App\Http\Controllers\GroupController::class, 'show_teacher'])->name('group.show.teacher');

        Route::match(['get','post'],'/group_show_students/{id}', [App\Http\Controllers\GroupController::class, 'show_students'])->name('group.show.students');

        Route::match(['get','post'],'/group_search', [App\Http\Controllers\GroupController::class, 'group_search'])->name('group.search');

        Route::get('/group_delete/{id}', [App\Http\Controllers\GroupController::class, 'delete'])->name('group.delete');

  
});

Route::middleware('teacherAccess')->group(function(){

                ///////////// PROFESSEUR HOME/////////////

             Route::get('/tasks_proceed', [App\Http\Controllers\HomeController::class, 'tasks_proceed'])->name('show.tasks.proceed');
             Route::get('/tasks_finish', [App\Http\Controllers\HomeController::class, 'tasks_finish'])->name('show.tasks.finish');
             Route::get('/tasks_expirate', [App\Http\Controllers\HomeController::class, 'tasks_expirate'])->name('show.tasks.expirate');
             Route::get('/teacher_groups', [App\Http\Controllers\HomeController::class, 'teacher_groups'])->name('show.teacher.groups');
             Route::get('/teacher_project_proceed', [App\Http\Controllers\HomeController::class, 'teacher_project_proceed'])->name('show.teacher.project.proceed');
             Route::get('/teacher_project_finish', [App\Http\Controllers\HomeController::class, 'teacher_project_finish'])->name('show.teacher.project.finish');

        ////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////// Project //////////////////////////////////// 
        ////////////////////////////////////////////////////////////////////////////////

        Route::get('/project_teacher_index', [App\Http\Controllers\ProjectController::class, 'index_teacher'])->name('projects_teacher');

        Route::match(['get','post'],'/project_teacher_create', [App\Http\Controllers\ProjectController::class, 'create_teacher'])->name('project_teacher.create');


        Route::match(['get','post'],'/project_teacher_edit/{id}', [App\Http\Controllers\ProjectController::class, 'edit_teacher'])->name('project_teacher.edit');

        Route::match(['get','post'],'/project_teacher_all_show/{id}', [App\Http\Controllers\ProjectController::class, 'show_teacher'])->name('project_teacher.all.show');

        Route::get('/project_teacher_delete/{id}', [App\Http\Controllers\ProjectController::class, 'delete_teacher'])->name('project_teacher.delete');

        Route::match(['get','post'],'/project_teacher_validate/{id}', [App\Http\Controllers\ProjectController::class, 'validate_teacher'])->name('project_validate');


        ////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////// Resource //////////////////////////////////// 
        ////////////////////////////////////////////////////////////////////////////////



        Route::match(['get','post'],'/resource_create/{project_id}', [App\Http\Controllers\ResourceController::class, 'create'])->name('resource.create');


        Route::match(['get','post'],'/resource_edit/{id}', [App\Http\Controllers\ResourceController::class, 'edit'])->name('resource.edit');

        Route::match(['get','post'],'/resource_delete/{id}', [App\Http\Controllers\ResourceController::class, 'delete'])->name('resource.delete');

        Route::match(['get','post'],'/resource_shows/{project_id}', [App\Http\Controllers\ResourceController::class, 'shows'])->name('resource.shows');

        Route::match(['get','post'],'/resource/{id}/download', [App\Http\Controllers\ResourceController::class, 'download'])->name('resource.download');



        ////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////// Task ////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////


        Route::match(['get','post'],'/task_create/{group_id}', [App\Http\Controllers\TaskController::class, 'create'])->name('task.create');


        Route::match(['get','post'],'/task_edit/{id}/{group_id}', [App\Http\Controllers\TaskController::class, 'edit'])->name('task.edit');

        Route::get('/task_show/{id}', [App\Http\Controllers\TaskController::class, 'show'])->name('task.show');

        Route::get('/task_delete/{id}', [App\Http\Controllers\TaskController::class, 'delete'])->name('task.delete');

          ////////////////////////////////////////////////////////////////////////////////
          //////////////////////////////////// Comment ////////////////////////////////////
          ////////////////////////////////////////////////////////////////////////////////

          Route::match(['get','post'],'/comment_create/{report_id}', [App\Http\Controllers\CommentController::class, 'create'])->name('comment.create');


          Route::match(['get','post'],'/comment_edit/{id}/{report_id}', [App\Http\Controllers\CommentController::class, 'edit'])->name('comment.edit');

          ///////////////////REPORT

        Route::match(['get','post'],'/report_download_teacher/{id}', [App\Http\Controllers\ReportController::class, 'download'])->name('report.download.teacher');

 
});

Route::middleware('studentAccess')->group(function(){

        ////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////// Project //////////////////////////////////// 
        ////////////////////////////////////////////////////////////////////////////////

        Route::get('/project_student_index', [App\Http\Controllers\ProjectController::class, 'index_student'])->name('projects_student');

        ////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////// Task ////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////


        Route::get('/task_student_show/{id}', [App\Http\Controllers\TaskController::class, 'show_student'])->name('task_student.show');

      ////////////////////////////////////////////////////////////////////////////////
      //////////////////////////////////// Report ////////////////////////////////////
      //////////////////////////////////////////////////////////////////////////////// 



      Route::match(['get','post'],'/report_create/{id}', [App\Http\Controllers\ReportController::class, 'create'])->name('report.create');

       Route::match(['get','post'],'/report_delete_file/{id}', [App\Http\Controllers\ReportController::class, 'delete_file'])->name('report.delete.file');


      Route::match(['get','post'],'/report_edit/{id}/{task_id}', [App\Http\Controllers\ReportController::class, 'edit'])->name('report.edit');



        ////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////// Resource //////////////////////////////////// 
        ////////////////////////////////////////////////////////////////////////////////




        Route::match(['get','post'],'/resource_shows_student/{project_id}', [App\Http\Controllers\ResourceController::class, 'shows_student'])->name('resource.shows.student');

        Route::match(['get','post'],'/resource/{id}/download_student', [App\Http\Controllers\ResourceController::class, 'download_student'])->name('resource.download.student');


   
});

      ////////////////////////////////////////////////////////////////////////////////
      //////////////////////////////////// Home ////////////////////////////////////
      ////////////////////////////////////////////////////////////////////////////////

Route::middleware(['auth'])->group(function () {
    
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');





             Route::match(['get','post'],'/modif_mdp', [App\Http\Controllers\HomeController::class, 'modif_mdp'])->name('modif.mdp');

});


      ////////////////////////////////////////////////////////////////////////////////
      //////////////////////////////////// Error ////////////////////////////////////
      ////////////////////////////////////////////////////////////////////////////////


      Route::get('/error_admin', [App\Http\Controllers\ErrorController::class, 'error_admin'])->name('error.admin');

      Route::get('/error_teacher', [App\Http\Controllers\ErrorController::class, 'error_teacher'])->name('error.teacher');

      Route::get('/error_student', [App\Http\Controllers\ErrorController::class, 'error_student'])->name('error.student');

      Route::get('/error_all', [App\Http\Controllers\ErrorController::class, 'error_all'])->name('error.all');


