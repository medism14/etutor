<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use App\Models\Comment;

use App\Models\Report;

use App\Models\Project;

use Illuminate\Support\Facades\DB;


class CommentController extends Controller
{
    public function __construct()
    {

        

        $this->creation_validation_rules = [
            'name' => 'required|max:255|string',
            'first_name' => 'required|max:255|string',
            'email' => 'required|unique:comments|email',
            'phone' => 'required|max:30|number',
            'password' => 'required|min:8',
            // 'role' => [Rule::in(array_keys(comment::$enum_role))],
        ];

        $this->comment_creation_validation_error_msg = [
            'name.required' => "Veuillez saisir le nom de l'utilisateur.",
            'first_name.required' => "Veuillez saisir le prenom de l'utilisateur.",
            'email.required' => "Veuillez saisir l'email de l'utilisateur.",
            'phone.required' => "Veuillez saisir le numéro de l'utilisateur.",
            // 'role.required' => "Veuillez saisir le rôle de l'utilisateur.",
            'email.unique' => 'Cette email est déjà pris par un autre utilisateur',
        ];

        $this->comment_own_edit_validation_error_msg = [
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
        
        $comments = Comment::all();

        return view('comment.index',compact('comments'));
    }
    /* 
        ******************************************************************************************
        ******************************************************************************************
        ******************************************************************************************
    */

    public function formulaire()
    {

        return view('comment.create');

    }

    public function create(Request $request, $report_id)
    {   

        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }
        
        if (!$report_id)
        {
            return redirect(route('error.all'));
        } 

            $project = Project::whereHas('group.tasks.report', function ($report) use ($report_id) {
                $report->where('id', $report_id);
            })->first();

        if ($project == null)
        {       

            $projects = Project::where('user_id','=',Auth()->user()->id)->with(['teacher' => function($query) {
            $query->where('role','=',1)
                  ->where('id','=',Auth()->user()->id);
        }])->get(); // Listes des projets

            $erreur = 'Vous ne pouvez pas accéder aux projets d\'autres professeurs';

            session()->flash('erreur',$erreur);

            return redirect(route('projects_teacher',compact('projects')));
        } else if ($project->user_id != Auth()->user()->id)
            {       

                $projects = Project::where('user_id','=',Auth()->user()->id)->with(['teacher' => function($query) {
                $query->where('role','=',1)
                      ->where('id','=',Auth()->user()->id);
            }])->get(); // Listes des projets

                $erreur = 'Vous ne pouvez pas accéder aux projets d\'autres professeurs';

                session()->flash('erreur',$erreur);

                return redirect(route('projects_teacher',compact('projects')));
            }

        if($request->isMethod('post'))
        {
            /*$validator = Validator::make($request->all(), $this->creation_validation_rules, $this->comment_creation_validation_error_msg);

            if ($validator->fails())
            {
                $messages = $validator->messages();
                return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
            }*/

            $new_comment = new Comment;
            $new_comment->description = $request->get('description');
            $new_comment->report_id = $report_id;
            $new_comment->save();

            $new_comment->delete();

            $new_comment = new Comment;
            $new_comment->description = $request->get('description');
            $new_comment->report_id = $report_id;
            $new_comment->save();

            $report = Report::findOrFail($report_id);

            session()->flash('result', 'Le commentaire a bien été ajouté');

            return redirect(route('task.show', ['id' => $report->task_id]))->with('message.success', 'modification correctement sauvegardé.');
        
        
        }
        return view('comment.create');
    }
    /* 
        ******************************************************************************************
        ******************************************************************************************
        ******************************************************************************************
    */
    public function edit(Request $request, $id, $report_id)
    {   
        
        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }
        
        if (!$report_id || !$id)
        {
            return redirect(route('error.all'));
        } 

        $project = Project::whereHas('group.tasks.report', function ($report) use ($report_id) {
                $report->where('id', $report_id);
            })->first();

        $comment = Comment::findOrFail($id);

        $report = Report::findOrFail($report_id);

        

        if($request->isMethod('post')){

            $edition_validation_rules = [
                'name' => 'required|max:255|string',
                'first_name' => 'required|max:255|string',
                'email' => 'unique:comments,email,' . $comment->id,
                'role' => '',
                'phone' => 'required|max:30|number',
                'password' => 'nullable|min:8',
                // 'role' => [Rule::in(array_keys(comment::$enum_role))],
            ];

            /*$validator = Validator::make($request->all(), $edition_validation_rules, $this->comment_creation_validation_error_msg);

            if ($validator->fails())
            {
                $messages = $validator->messages();
                // dd($validator->errors());
                return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
            }*/

            $comment->description = $request->get('description');
            $comment->report_id = $report_id;   

            $comment->save();

            session()->flash('result', 'Le commentaire a bien été modifié');

            return redirect(route('task.show', ['id' => $report->task_id]));
        }

        return view('comment.edit',compact('comment'));
    }
    /*
        ******************************************************************************************
        ******************************************************************************************
        ******************************************************************************************
    */






}
