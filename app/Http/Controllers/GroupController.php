<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use App\Models\Group;

use App\Models\Studentgroup;

use App\Models\Project;

use App\Models\User;

use Illuminate\Support\Facades\DB;


class GroupController extends Controller
{
    public function __construct()
    {

        

        $this->creation_validation_rules = [
            'name' => 'required|max:255|string',
            'first_name' => 'required|max:255|string',
            'email' => 'required|unique:groups|email',
            'phone' => 'required|max:30|number',
            'password' => 'required|min:8',
            // 'role' => [Rule::in(array_keys(group::$enum_role))],
        ];

        $this->group_creation_validation_error_msg = [
            'name.required' => "Veuillez saisir le nom de l'utilisateur.",
            'first_name.required' => "Veuillez saisir le prenom de l'utilisateur.",
            'email.required' => "Veuillez saisir l'email de l'utilisateur.",
            'phone.required' => "Veuillez saisir le numéro de l'utilisateur.",
            // 'role.required' => "Veuillez saisir le rôle de l'utilisateur.",
            'email.unique' => 'Cette email est déjà pris par un autre utilisateur',
        ];

        $this->group_own_edit_validation_error_msg = [
            'name.required' => 'Veuillez entrer votre nom.',
            'first_name.required' => 'Veuillez entrer votre prenom.',
            'email.required' => 'Veuillez saisir votre email.',
            'email.unique' => 'Cette email est déjà pris par un autre utilisateur',
            'password.required' => 'Veuillez saisir un mot de passe supérieur à 8 caractère.',
            // 'role.required' => 'Veuillez définir votre rôle.',
        ];
    }
    /* 
        ******************************************************************************************
        ******************************************************************************************
        ******************************************************************************************
    */
    public function index()
    {

        /*$groups = DB::select('SELECT groups.*,users.name,projects.title
                                FROM groups,users,projects
                                WHERE groups.user_id = users.id
                                AND groups.project_id = projects.id');*/

        $groups = Group::with(['project' => function($project){
            $project->with('teacher');
        }])->get();

        $students = User::whereIn('id',function($query)
        {
            $query->select('user_id')
                   ->from('studentgroups');
        })->get();

        $teachers = User::whereIn('id',function($query)
        {
            $query->select('user_id')
                   ->from('projects')
                   ->whereIn('id',function($query2)
                   {
                        $query2->select('project_id')
                               ->from('groups');
                   });
        })->get();

        // dd($teachers);

        return view('group.index',compact('groups','students','teachers'));
    }
    /* 
        ******************************************************************************************
        ******************************************************************************************
        ******************************************************************************************
    */

    public function formulaire()
    {

        return view('group.create');

    }

    public function create(Request $request)
    {

        /*  $students = DB::select('SELECT users.*
                              FROM users
                              WHERE role = ?',[2]);*/

        $projects = Project::whereNotIn('id', function($query) 
                 {
                     $query->select('project_id')
                           ->from('groups');
                 })
                 ->get();

        $teachers = User::where('role', '=', 1)->get();

        $students = User::where('role', '=', 2)->whereNotIn('id', function($query) 
                 {
                     $query->select('user_id')
                           ->from('studentgroups');
                 })
                 ->get();

        $num_students = User::where('role', '=', 2)->whereNotIn('id', function($query) 
                 {
                     $query->select('user_id')
                           ->from('studentgroups');
                 })
                 ->count();


        if($request->isMethod('post'))
        {
            /*$validator = Validator::make($request->all(), $this->creation_validation_rules, $this->group_creation_validation_error_msg);

            if ($validator->fails())
            {
                $messages = $validator->messages();
                return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
            }*/

            if ($request->get('student1') == null)
            {
                redirect(route('group.create'));
            }
            else{


            
           if ($request->get('student5') != null)
            {
                $students_g = array($request->get('student1'),$request->get('student2'),$request->get('student3'),$request->get('student4'),$request->get('student5'));
            }
           if ($request->get('student5') == null)
            {
                $students_g = array($request->get('student1'),$request->get('student2'),$request->get('student3'),$request->get('student4'));
            }
            if ($request->get('student4') == null)
            {
                $students_g = array($request->get('student1'),$request->get('student2'),$request->get('student3'));
            }
            if ($request->get('student3') == null)
            {
                $students_g = array($request->get('student1'),$request->get('student2'));
            }
            if ($request->get('student2') == null)
            {   

                $students_g = array($request->get('student1'));
            }
            if ($request->get('student1') == null)
            {   

                $students_g = array();
            }


            $new_group = new Group;
            $new_group->project_id = $request->get('project_id');

            $new_group->save(); 

            //dd($request->get('student1'),$request->get('student2'),$request->get('student3'),$request->get('student4'),$request->get('student5'),$i);

            foreach ($students_g as $student)
            {

                $new_row_groups = new Studentgroup;

                $new_row_groups->user_id = $student;

                $new_row_groups->group_id = $new_group->id;

                $new_row_groups->save();

                

            }


            
            session()->flash('result', 'Le groupe a bien été créé');
            return redirect(route('groups'))->with('message.success', 'Enregistrement correctement créé.');
        
        
        }
    }
        return view('group.create',compact('projects','teachers','students','num_students'));
    }
    /* 
        ******************************************************************************************
        ******************************************************************************************
        ******************************************************************************************
    */
    public function edit(Request $request,$id)
    {

        if (!$id)
        {
            return redirect(route('error.all'));
        } 

        $group = Group::findOrFail($id);

        $project_id_actuel = $group->project_id;

        $projects = Project::whereNotIn('id',function($query)
        {
            $query->select('project_id')
                  ->from('groups');
        }   
        )->orwhereIn('id',[$project_id_actuel])->get();

        $students_nogroup = User::where('role', '=', 2)->whereNotIn('id', function($query) 
                 {
                     $query->select('user_id')
                           ->from('studentgroups');
                 })
                 ->get();


        $students_group = Studentgroup::where('group_id','=',$group->id)->with(['student'])->get();

        $nombre_students = Studentgroup::where('group_id','=',$group->id)->with(['student'])->count();

        if($request->isMethod('post')){

            $edition_validation_rules = [
                'name' => 'required|max:255|string',
                'first_name' => 'required|max:255|string',
                'email' => 'unique:groups,email,' . $group->id,
                'role' => '',
                'phone' => 'required|max:30|number',
                'password' => 'nullable|min:8',
                // 'role' => [Rule::in(array_keys(group::$enum_role))],
            ];

            if ($request->get('student5') != null){
                $students_g = array($request->get('student1'),$request->get('student2'),$request->get('student3'),$request->get('student4'),$request->get('student5'));
            }
           if ($request->get('student5') == null)
            {
                $students_g = array($request->get('student1'),$request->get('student2'),$request->get('student3'),$request->get('student4'));
            }
            if ($request->get('student4') == null)
            {
                $students_g = array($request->get('student1'),$request->get('student2'),$request->get('student3'));
            }
            if ($request->get('student3') == null)
            {
                $students_g = array($request->get('student1'),$request->get('student2'));
            }
            if ($request->get('student2') == null)
            {
                $students_g = array($request->get('student1'));
            }
            if ($request->get('student1') == null)
            {
                $students_g = array();
            }

                
                Studentgroup::where('group_id', '=', $group->id)->delete();


            foreach ($students_g as $student)
            {

                
                $new_row_groups = new Studentgroup;

                $new_row_groups->user_id = $student;

                $new_row_groups->group_id = $group->id;

                $new_row_groups->save();
                

            }



            if ($group->project_id == $request->get('project_id'))
            {

            }else
            {
                

                if ($group->tasks) {

                            foreach ($group->tasks as $task)
                            {
                                if ($task->report)
                                {   
                                    if ($task->report->comment)
                                    {
                                        $task->report->comment->delete();
                                    }

                                    $task->report->delete();
                                }
                            }

                            $group->tasks()->delete();
                                }

            }

            $group->project_id = $request->get('project_id');
            

            $group->save();

            session()->flash('result', 'Le groupe a bien été modifié');

            return redirect(route('groups'))->with('message.success', 'modification correctement sauvegardé.');
        }

        return view('group.edit',compact('group','projects','project_id_actuel','students_group','students_nogroup','nombre_students'));
    }
    /*
        ******************************************************************************************
        ******************************************************************************************
        ******************************************************************************************
    */
    public function show(Request $request,$id)
    {

        if (!$id)
        {
            return redirect(route('error.all'));
        } 

        $group = Group::findOrFail($id);

        return view('group.show',compact('group'));
    }

        public function group_search(Request $request)
    {   
            
        $students = User::whereIn('id',function($query)
        {
            $query->select('user_id')
                   ->from('studentgroups');
        })->get();

        $teachers = User::whereIn('id',function($query)
        {
            $query->select('user_id')
                   ->from('projects')
                   ->whereIn('id',function($query2)
                   {
                        $query2->select('project_id')
                               ->from('groups');
                   });
        })->get();


        $filter = $request->get('filter');

        if ($filter == 'students')
        {
            $id = $request->get('students');

            $groups = Group::whereHas('studentgroup', function($query) use ($id){
                $query->where('user_id',$id);
            })->with(['project' => function($project){
            $project->with('teacher');
        }])->get();
        }
        else
        {
            $id = $request->get('teachers');

            $groups = Group::whereHas('project', function($query) use ($id){
                $query->where('user_id',$id);
            })->with(['project' => function($project){
            $project->with('teacher');
        }])->get();


        }

            $search = 1;
        return view('group.index',compact('groups','search','students','teachers'));

    }

       /* 
        ******************************************************************************************
        ******************************************************************************************
        ******************************************************************************************
    */

    public function show_project(Request $request,$id)
    {

        if (!$id)
        {
            return redirect(route('error.all'));
        } 

         $group = Group::findOrFail($id);

         $project_id = $group->project_id;

         $project = Project::findOrFail($project_id);

        return view('group.show_project',compact('group','project'));
    }
       /* 
        ******************************************************************************************
        ******************************************************************************************
        ******************************************************************************************
    */
    public function show_teacher(Request $request,$id)
    {

        if (!$id)
        {
            return redirect(route('error.all'));
        } 

        $groups = Group::where('id','=',$id)->with(['project' => function($project) {
            $project->with(['teacher']);
        }])->get();


        return view('group.show_teacher',compact('groups'));
    }
   /* 
        ******************************************************************************************
        ******************************************************************************************
        ******************************************************************************************
    */
    public function show_students(Request $request,$id)
    {

        if (!$id)
        {
            return redirect(route('error.all'));
        } 

         $group = Group::findOrFail($id);

         $group_id = $group->id;

         $students = Studentgroup::where('group_id','=',$group_id)->with('student')->get();

        return view('group.show_students',compact('students'));
    }
   /* 
        ******************************************************************************************
        ******************************************************************************************
        ******************************************************************************************
    */
    public function delete(Request $request,$id)
    {

        if (!$id)
        {
            return redirect(route('error.all'));
        } 

        $group = Group::findOrFail($id);

        $students = Studentgroup::where('group_id','=',$group->id)->get();

            foreach($students as $student)
            {
                $student->delete();
            }

            if ($group) {
                        $group->studentgroup()->delete();
                        
                        if ($group->tasks) {

                            foreach ($group->tasks as $task)
                            {
                                if ($task->report)
                                {   
                                    if ($task->report->comment)
                                    {
                                        $task->report->comment->delete();
                                    }

                                    $task->report->delete();
                                }
                            }

                            $group->tasks()->delete();
                        }

                        $group->delete();
                    }

            session()->flash('delete', 'Le groupe a bien été supprimé');

        return redirect()->back()->with('message.success', 'Enregistrement correctement supprimé');
    }



    public function block_group(Request $request,$id,$type)
    {
        dd($request->all(),$id,$type);
        $group = Group::findOrFail($id);
        if(intval($type)){
            $group->blocked = 0;
        }else{
            $group->blocked = 1;
        }        
        $group->save();

        return redirect()->back()->with('message.success', 'L\'utilisateur à été correctement blocqué.');
    }



}
