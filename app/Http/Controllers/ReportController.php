<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use App\Models\Report;

use App\Models\Task;
use App\Models\Project;

use Illuminate\Support\Facades\DB;

use Carbon\Carbon;


class ReportController extends Controller
{
    public function __construct()
    {

        

        $this->creation_validation_rules = [
            'name' => 'required|max:255|string',
            'first_name' => 'required|max:255|string',
            'email' => 'required|unique:reports|email',
            'phone' => 'required|max:30|number',
            'password' => 'required|min:8',
            // 'role' => [Rule::in(array_keys(report::$enum_role))],
        ];

        $this->report_creation_validation_error_msg = [
            'name.required' => "Veuillez saisir le nom de l'utilisateur.",
            'first_name.required' => "Veuillez saisir le prenom de l'utilisateur.",
            'email.required' => "Veuillez saisir l'email de l'utilisateur.",
            'phone.required' => "Veuillez saisir le numéro de l'utilisateur.",
            // 'role.required' => "Veuillez saisir le rôle de l'utilisateur.",
            'email.unique' => 'Cette email est déjà pris par un autre utilisateur',
        ];

        $this->report_own_edit_validation_error_msg = [
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

        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }
        
        $reports = Report::all();

        return view('report.index',compact('reports'));
    }
    /* 
        ******************************************************************************************
        ******************************************************************************************
        ******************************************************************************************
    */

    public function formulaire()
    {

        return view('report.create');

    }

    public function create(Request $request,$id)
    {   

        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }
        
        if (!$id)
        {
            return redirect(route('error.all'));
        } 

        $i = 0; 
        $project = Project::whereHas('group.tasks', function($tasks) use ($id)
            {
                $tasks->where('id',$id);
            })->with('group.studentgroup.student')->first();

        foreach($project->group->studentgroup as $studentgroup)
        {
            if ($studentgroup->student->id == Auth()->user()->id){
                $i = 667;
            }
        }

        if ($project == null)
        {       
                $currentDateTime = date('Y-m-d H:i:s', strtotime('+3 hours'));

            $project = Project::whereIn('id',function ($query) use ($currentDateTime){
            $query->select('project_id')
                ->from('groups')
                ->whereIn('id', function($query) use ($currentDateTime){
                    $query->select('group_id')
                        ->from('studentgroups')
                        ->where('user_id','like',auth()->user()->id);
                });
        })->with(['group' => // Project en questions
            function($project_group) use ($currentDateTime)
            {
                $project_group->with(['tasks' =>
                    function ($tasks) use ($currentDateTime)
                    {
                        $tasks->with(['report' =>
                            function ($report)  
                            {
                                $report->with(['comment']);
                            }
                        ])->where('end_date','>',$currentDateTime);
                    },
                    'studentgroup' =>
                    function($studentgroup)
                    {
                        $studentgroup->with(['student']);
                    }
                ]);
            }
        ])->first();

                    $erreur = 'Vous ne pouvez pas accéder aux projets d\'autres etudiants';

                    session()->flash('erreur',$erreur);

                    return redirect(route('projects_student',compact('project')));
        }else if ($i != 667)
            {       
                    $currentDateTime = date('Y-m-d H:i:s', strtotime('+3 hours'));
                
            $project = Project::whereIn('id',function ($query) use ($currentDateTime){
                $query->select('project_id')
                    ->from('groups')
                    ->whereIn('id', function($query) use ($currentDateTime){
                        $query->select('group_id')
                            ->from('studentgroups')
                            ->where('user_id','like',auth()->user()->id);
                    });
            })->with(['group' => // Project en questions
                function($project_group) use ($currentDateTime)
                {
                    $project_group->with(['tasks' =>
                        function ($tasks) use ($currentDateTime)
                        {
                            $tasks->with(['report' =>
                                function ($report)  
                                {
                                    $report->with(['comment']);
                                }
                            ])->where('end_date','>',$currentDateTime);
                        },
                        'studentgroup' =>
                        function($studentgroup)
                        {
                            $studentgroup->with(['student']);
                        }
                    ]);
                }
            ])->first();

                        $erreur = 'Vous ne pouvez pas accéder aux projets d\'autres etudiants';

                        session()->flash('erreur',$erreur);

                        return redirect(route('projects_student',compact('project')));
            }


        if($request->isMethod('post'))
        {
            /*$validator = Validator::make($request->all(), $this->creation_validation_rules, $this->report_creation_validation_error_msg);

            if ($validator->fails())
            {
                $messages = $validator->messages();
                return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
            }*/

            $currentDateTime = date('Y-m-d H:i:s', strtotime('+3 hours'));




                
                



            $new_report = new Report;
            $new_report->date = $currentDateTime;
            $new_report->description = $request->get('description');

            $file = $request->file('fichier');    

            if ($file == null)
            {
                $new_report->fichier = null;
            }
            else
            {   
                $originalName = $file->getClientOriginalName();

                $filePath = $file->storeAs('compte_rendu', $originalName);

                $new_report->fichier = $filePath;
            }
            
            $new_report->task_id = $id;
            $new_report->save();

            session()->flash('result', 'Le compte-rendu a bien été créé');

            return redirect(route('task_student.show', ['id' => $id]))->with('message.success', 'modification correctement sauvegardé.');
        
        
        }

    }
    /* 
        ******************************************************************************************
        ******************************************************************************************
        ******************************************************************************************
    */

        public function download ($id)
    {       

        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }
        
        if (!$id)
        {
            return redirect(route('error.all'));
        } 

            $i = 0;
        $project = Project::whereHas('group.tasks.report', function($report) use ($id)
            {
                $report->where('id',$id);
            })->with('group.studentgroup.student')->first();


            if ($project->user_id == Auth()->user()->id)
            {
                $i = 667;
            }

            if ($project == null)
            {
                $projects = Project::where('user_id','=',Auth()->user()->id)->with(['teacher' => function($query) {
                $query->where('role','=',1)
                      ->where('id','=',Auth()->user()->id);
            }])->get(); // Listes des projets

                $erreur = 'Vous ne pouvez pas accéder aux projets d\'autres professeurs';

                session()->flash('erreur',$erreur);

                return redirect(route('projects_teacher',compact('projects')));
            }
            else if ($i != 667)
            {       

                $projects = Project::where('user_id','=',Auth()->user()->id)->with(['teacher' => function($query) {
                $query->where('role','=',1)
                      ->where('id','=',Auth()->user()->id);
            }])->get(); // Listes des projets

                $erreur = 'Vous ne pouvez pas accéder aux projets d\'autres professeurs';

                session()->flash('erreur',$erreur);

                return redirect(route('projects_teacher',compact('projects')));
            }

            $report = Report::findOrFail($id);

            $file_path = storage_path('app/'.$report->fichier);

            return response()->download($file_path);

            return redirect()->back()->with('message.success', 'Téléchargement parfaitemenet effectué');
    }

    public function edit(Request $request, $id, $task_id)
    {   

        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }
        
        if (!$id || !$task_id)
        {
            return redirect(route('error.all'));
        } 

        $i = 0;
        $project = Project::whereHas('group.tasks.report', function($report) use ($id)
            {
                $report->where('id',$id);
            })->with('group.studentgroup.student')->first();

        foreach($project->group->studentgroup as $studentgroup)
        {
            if ($studentgroup->student->id == Auth()->user()->id){
                $i = 667;
            }
        }

        if ($project == null)
        {       
                $currentDateTime = date('Y-m-d H:i:s', strtotime('+3 hours'));

            $project = Project::whereIn('id',function ($query) use ($currentDateTime){
            $query->select('project_id')
                ->from('groups')
                ->whereIn('id', function($query) use ($currentDateTime){
                    $query->select('group_id')
                        ->from('studentgroups')
                        ->where('user_id','like',auth()->user()->id);
                });
        })->with(['group' => // Project en questions
            function($project_group) use ($currentDateTime)
            {
                $project_group->with(['tasks' =>
                    function ($tasks) use ($currentDateTime)
                    {
                        $tasks->with(['report' =>
                            function ($report)  
                            {
                                $report->with(['comment']);
                            }
                        ])->where('end_date','>',$currentDateTime);
                    },
                    'studentgroup' =>
                    function($studentgroup)
                    {
                        $studentgroup->with(['student']);
                    }
                ]);
            }
        ])->first();

                    $erreur = 'Vous ne pouvez pas accéder aux projets d\'autres etudiants';

                    session()->flash('erreur',$erreur);

                    return redirect(route('projects_student',compact('project')));
        }else if ($i != 667)
            {       
                    $currentDateTime = date('Y-m-d H:i:s', strtotime('+3 hours'));
                
            $project = Project::whereIn('id',function ($query) use ($currentDateTime){
                $query->select('project_id')
                    ->from('groups')
                    ->whereIn('id', function($query) use ($currentDateTime){
                        $query->select('group_id')
                            ->from('studentgroups')
                            ->where('user_id','like',auth()->user()->id);
                    });
            })->with(['group' => // Project en questions
                function($project_group) use ($currentDateTime)
                {
                    $project_group->with(['tasks' =>
                        function ($tasks) use ($currentDateTime)
                        {
                            $tasks->with(['report' =>
                                function ($report)  
                                {
                                    $report->with(['comment']);
                                }
                            ])->where('end_date','>',$currentDateTime);
                        },
                        'studentgroup' =>
                        function($studentgroup)
                        {
                            $studentgroup->with(['student']);
                        }
                    ]);
                }
            ])->first();

                        $erreur = 'Vous ne pouvez pas accéder aux projets d\'autres etudiants';

                        session()->flash('erreur',$erreur);

                        return redirect(route('projects_student',compact('project')));
            }

        $report = Report::findOrFail($id);

        $currentDateTime = date('Y-m-d H:i:s', strtotime('+3 hours'));

        if($request->isMethod('post')){

            $edition_validation_rules = [
                'name' => 'required|max:255|string',
                'first_name' => 'required|max:255|string',
                'email' => 'unique:reports,email,' . $report->id,
                'role' => '',
                'phone' => 'required|max:30|number',
                'password' => 'nullable|min:8',
                // 'role' => [Rule::in(array_keys(report::$enum_role))],
            ];

            $file = $request->file('fichier');    

            if ($file == null)
            {

            }
            else
            {   
                $originalName = $file->getClientOriginalName();

            // Vérifier la taille du fichier
                if ($file->getSize() > 50 * 1024 * 1024) {
                    return redirect()
                        ->back()
                        ->withErrors(['erreur' => 'Le fichier ne doit pas dépasser 50 Mo.'])
                        ->withInput();
                }
                
                $filePath = $file->storeAs('compte_rendu', $originalName);

                $report->fichier = $filePath;
            }

            $report->date = $currentDateTime;
            $report->description = $request->get('description');
            $report->task_id = $task_id;
            

            $report->save();

            session()->flash('result', 'Le compte-rendu a bien été modifié');

            return redirect(route('task_student.show', ['id' => $task_id]))->with('message.success', 'modification correctement sauvegardé.');
        }

    }
    /*
        ******************************************************************************************
        ******************************************************************************************
        ******************************************************************************************
    */
    public function delete_file(Request $request,$id)
    {

        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }
        
        if (!$id)
        {
            return redirect(route('error.all'));
        } 

        $report = Report::findOrFail($id);

        $report->fichier = null;

        $report->save();

        return redirect()->back();

    }
    public function show(Request $request,$id)
    {

        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }
        
        if (!$id)
        {
            return redirect(route('error.all'));
        } 

        $report = Report::findOrFail($id);

        return view('report.show',compact('report'));
    }


    public function block_report(Request $request,$id,$type)
    {

        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }
        
        if (!$id)
        {
            return redirect(route('error.all'));
        } 
        
        dd($request->all(),$id,$type);
        $report = Report::findOrFail($id);
        if(intval($type)){
            $report->blocked = 0;
        }else{
            $report->blocked = 1;
        }        
        $report->save();

        return redirect()->back()->with('message.success', 'L\'utilisateur à été correctement blocqué.');
    }



}
