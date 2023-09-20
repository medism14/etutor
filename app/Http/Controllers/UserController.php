<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use App\Models\User;

use App\Models\Project;
use App\Models\Group;
use App\Models\Studentgroup;


class UserController extends Controller
{
    public function __construct()
    {



        $this->creation_validation_rules = [
            'name' => 'required|max:255|string',
            'first_name' => 'required|max:255|string',
            'email' => 'required|unique:users|email',
            'phone' => 'required|max:30|number',
            'password' => 'required|min:8',
            // 'role' => [Rule::in(array_keys(User::$enum_role))],
        ];

        $this->user_creation_validation_error_msg = [
            'name.required' => "Veuillez saisir le nom de l'utilisateur.",
            'first_name.required' => "Veuillez saisir le prenom de l'utilisateur.",
            'email.required' => "Veuillez saisir l'email de l'utilisateur.",
            'phone.required' => "Veuillez saisir le numéro de l'utilisateur.",
            // 'role.required' => "Veuillez saisir le rôle de l'utilisateur.",
            'email.unique' => 'Cette email est déjà pris par un autre utilisateur',
        ];

        $this->user_own_edit_validation_error_msg = [
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



        // $users = User::all();

        $users = User::with(['teachers_projects'])->with('students.group')->orderBy('role','ASC')->get();

        
/*        foreach ($users as $user) {
            $group = optional($user->students)->group;
            if ($group) {
                dump($group);
            }
        }*/

        return view('user.index',compact('users'));
    }
    /* 
        ******************************************************************************************
        ******************************************************************************************
        ******************************************************************************************
    */

    public function formulaire()
    {

        return view('user.create');

    }

    

    public function create(Request $request)
{   
    function tab($chaine){
        $i=0;
        $row = 1;
        $name = '';
        $first_name = '';
        $role = '';
        $email = '';
        $phone = '';
        for ($i=0 ; $i<strlen($chaine) ; $i++)
        {

            if ($chaine[$i] == ';')
            {
                $row++;
                continue;
            }

            if ($row == 1)
            {
                $name .= $chaine[$i];              
            }
            else if ($row == 2)
            {
                $first_name .= $chaine[$i];
            }
            else if ($row == 3)
            {
                $role .= $chaine[$i];
            }
            else if ($row == 4)
            {
                $email .= $chaine[$i];
            }
            else if ($row == 5)
            {
                $phone .= $chaine[$i];
            }

        }

        $cols = array($name,$first_name,$role,$email,$phone);

        return $cols;

    }

    if($request->isMethod('post'))
    {   
        if($request->hasFile('csv_file'))
        {   
            $file = $request->file('csv_file');
            $csvData = file_get_contents($file);
            $rows = array_map('str_getcsv', explode("\n", $csvData));
            $header = array_shift($rows);
            $i=0;
            foreach ($rows as $row) {
                    
                    $cols = tab($row[0]);
                    if ($cols[0] == ""){
                        break;
                    }
                $new_user = new User;
                $new_user->first_connection = 1;
                $new_user->name = $cols[0];
                $new_user->first_name = $cols[1];
                $new_user->role = intval($cols[2]);
                $new_user->email = $cols[3];
                $new_user->phone = intval($cols[4]);
                $prenom_mdp = strtolower($cols[1]);
                $generated_password = $prenom_mdp . '123'; 
                $new_user->password = bcrypt($generated_password);
                // dump($new_user);
                $new_user->save();
                $i++;
                                    }
                session()->flash('result', ($i > 1) ? "Les utilisateurs ont bien étés créé(e)s" : "L'utilisateur a bien été créé(e)");

            return redirect(route('users'))->with('message.success', 'modification correctement sauvegardé.');
        }else{
            $generated_password = $request->get('password');
            $new_user = new User;
            $new_user->first_connection = 1;
            $new_user->name = $request->get('name');
            $new_user->first_name = $request->get('first_name');
            $new_user->role = $request->get('role');
            $new_user->email = $request->get('email');
            $new_user->phone = $request->get('phone');
            $new_user->password = bcrypt($generated_password);
            $new_user->save();

            session()->flash('result', 'L\'utilisateur a bien été créé(e)');

            return redirect(route('users'))->with('message.success', 'modification correctement sauvegardé.');
        }

    }
    else
    {
    return view('user.create');
    }


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

        $user = User::findOrFail($id);

        if($request->isMethod('post')){

            $edition_validation_rules = [
                'name' => 'required|max:255|string',
                'first_name' => 'required|max:255|string',
                'email' => 'unique:users,email,' . $user->id,
                'role' => '',
                'phone' => 'required|max:30|number',
                'password' => 'nullable|min:8',
                // 'role' => [Rule::in(array_keys(User::$enum_role))],
            ];

            /*$validator = Validator::make($request->all(), $edition_validation_rules, $this->user_creation_validation_error_msg);

            if ($validator->fails())
            {
                $messages = $validator->messages();
                // dd($validator->errors());
                return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
            }*/

            $user->name = $request->get('name');
            $user->first_name = $request->get('first_name');
            $user->role = $request->get('role');
            $user->email = $request->get('email');
            $user->phone = $request->get('phone');

            if($request->password == ''){

            }else{
                $user->password = bcrypt($request->get('password'));
            }

            $user->save();

            session()->flash('result', 'L\'utilisateur a bien été modifié');

            return redirect(route('users'))->with('message.success', 'modification correctement sauvegardé.');
        }


        return view('user.edit',compact('user'));
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

         $user = User::findOrFail($id);

        $projects = Project::where('user_id','=',"$id")->get();


        return view('user.show',compact('projects','user'));
    }  

    public function show_group(Request $request,$group_id)
    {

        if (!$group_id)
        {
            return redirect(route('error.all'));
        } 

         $group = Group::where('id',$group_id)->with('studentgroup.student')->with('project')->first();

        return view('user.show_group_user',compact('group'));
    } 

    public function delete(Request $request,$id)
    {   

        if (!$id)
        {
            return redirect(route('error.all'));
        } 

        $user = User::findOrFail($id);

        if ($user->role == 2)
        {

            // Vérifier si l'utilisateur est associé à un groupe
                if ($user->students) {
                    // Récupérer le groupe associé à l'utilisateur
                    $group = $user->students->group;
                    
                    // Supprimer l'enregistrement de la table studentgroups
                    $user->students->delete();
                    
                    // Vérifier si le groupe associé à l'utilisateur n'a plus de clé dans la table studentgroups
                    if ($group->studentgroup->isEmpty()) {
                        
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
                }

        }

        if($user->role == 1)
        {

            /*$projects = Project::where('user_id',$id)->with('group', function($group)
                {
                    $group->with('studentgroup')->with('tasks.report.comment');
                })->get();

            if ($projects isNotEmpty())
            {

                foreach ($projects as $project)
                {
                            
                    if ($project->group != null)
                    {
                        //Supression studentgroup
                        foreach ($project->group->studentgroup as $studentgroup)
                        {
                            $studentgroup->delete();
                        }

                        //Supression group
                        $group = $project->group;
                        $group->delete();
                    }

                    if ($project->group->tasks->isNotEmpty())
                    {
                        foreach ($project->group->tasks as $task)
                        {
                            $task->
                        }
                    }

                }


                }*/

        $project = Project::where('user_id', $id)->first();

                if ($project) {
                    if ($project->group) {
                        $project->group->studentgroup()->delete();
                        
                        if ($project->group->tasks) {

                            foreach ($project->group->tasks as $task)
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

                            $project->group->tasks()->delete();
                        }

                        $project->group()->delete();
                    }

                    if ($project->resource) { // Vérifie s'il y a des ressources liées au projet
                        $project->resource()->delete(); // Supprime tous les enregistrements liés dans la table resources
                    }

                    $project->delete();
                }


            
            

        }
        
        session()->flash('delete', 'L\'utilisateur a bien été supprimé');

         $user->delete();

        return redirect()->back()->with('message.success', 'Enregistrement correctement supprimé');
    }   

    public function user_search(Request $request)
    {   
        $role = $request->get('role');
        $search = $request->get('search');
        $filter = $request->get('filter');

        $role = intval($role);

        function transform_string($string) {
          $result = [
            'lower' => strtolower($string),
            'upper' =>strtoupper($string),
            'first_word' =>ucfirst(strtolower($string))
          ];
          return $result;
        }

        if ($filter == 'first_name')
        {   

            $result = transform_string($search);

            $users = User::where('role', $role)
             ->where(function($query) use ($filter, $result) {
                 $query->where($filter, $result['upper'])
                       ->orWhere($filter, $result['lower'])
                       ->orWhere($filter, $result['first_word']);
             })
             ->get();

        }
        else if ($filter == 'email')
        {

            $users = User::where('role',$role)
            ->where('email',$search)
            ->get();

        }
        else if ($filter == 'phone')
        {

            $users = User::where('role',$role)
            ->where('phone',$search)
            ->get();

        }

            $search = 1;
        return view('user.index',compact('users','search'));


    }

    public function user_students(Request $request)
    {   
        $role = '2';

            $users = User::where('role',$role)
            ->get();

            $search = 1;
        return view('user.index',compact('users','search'));


    }

    public function user_teachers(Request $request)
    {   
        $role = '1';

            $users = User::where('role',$role)
            ->get();

            $search = 1;
        return view('user.index',compact('users','search'));


    }

    public function block_user(Request $request,$id,$type)
    {
        dd($request->all(),$id,$type);
        $user = User::findOrFail($id);
        if(intval($type)){
            $user->blocked = 0;
        }else{
            $user->blocked = 1;
        }        
        $user->save();

        return redirect()->back()->with('message.success', 'L\'utilisateur à été correctement blocqué.');
    }



}
