<?php

namespace App\Http\Controllers;
                                
use Illuminate\Http\Request;    
                            
use App\Models\Project;     
                        
use App\Models\User;
                        
use App\Models\Group;

use App\Models\Task;

use DateTime;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    /*
    *****************************************************************************************************************************************Administrateur***********************************************************************************************************************************************
    */

    public function index()
    {   

        if (auth()->user()->role == 0) //Administrateur
        {
            $count_projects_proceed = Project::where('state', '=', 'en cours')->count();
        $count_projects_finish = Project::where('state', '=', 'fini')->count();
        $count_projects_whithout_groups = Project::whereNotIn('id', function ($query) {
            $query->select('project_id')->from('groups');
        })->count();
        $count_students = User::where('role', 'like', 2)->count();
        $count_teachers = User::where('role', 'like', 1)->count();
        $count_groups = Group::all()->count();

        return view('home', compact('count_projects_proceed', 'count_projects_finish', 'count_projects_whithout_groups', 'count_students', 'count_teachers', 'count_groups'));
        }
        else if (auth()->user()->role == 1) // Professeur
        {   

            if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }else
            {

            $currentDate = new DateTime();

            $teacher = auth()->user()->id;

            $count_tasks_proceed = Task::whereHas('group',function($group) use ($teacher)
                {
                    $group->whereHas('project',function($project) use ($teacher)
                    {
                        $project->where('user_id',$teacher);
                    });
                })->where('end_date','>',$currentDate)->whereDoesntHave('report')->count();

            $count_tasks_finish = Task::whereHas('group',function($group) use ($teacher)
                {
                    $group->whereHas('project',function($project) use ($teacher)
                    {
                        $project->where('user_id',$teacher);
                    });
                })->whereHas('report',function ($report)
                {
                    $report->whereDoesntHave('comment');
                })->count();

            $count_tasks_expirate = Task::whereHas('group',function($group) use ($teacher)
                {
                    $group->whereHas('project',function($project) use ($teacher)
                    {
                        $project->where('user_id',$teacher);
                    });
                })->whereDoesntHave('report')->where('end_date','<',$currentDate)->count();

            $count_teacher_groups = Group::whereHas('project',function ($project) use ($teacher)
                {
                    $project->where('user_id',$teacher);
                })
                ->with(['project','studentgroup' => function($studentgroup)
                {
                    $studentgroup->with('student');
                }])
                ->count();

            $count_teacher_proceed_project = Project::where('user_id',$teacher)->where('state','en cours')->with(['teacher'])->count();

            $count_teacher_finish_project = Project::where('user_id',$teacher)->where('state','fini')->with(['teacher'])->count();



            return view('home',compact('count_tasks_proceed','count_tasks_finish','count_tasks_expirate','count_teacher_groups','count_teacher_proceed_project','count_teacher_finish_project'));
            }
        }
        else // Etudiant
        {

            if (auth()->user()->first_connection == 1)
            {   
                $first = 667;
                return view('modif_mdp', compact('first'));

            }

            return redirect()->route('projects_student');
        }

        
    }

    public function modif_mdp(Request $request)
    {

        if($request->isMethod('post'))
        {

            $user = User::findOrFail(auth()->user()->id);

            $generated_password = $request->get('password');
            $user->password = bcrypt($generated_password);
            $user->save();

            if (auth()->user()->role == 2)
            {   
            
                $user = User::findOrFail(auth()->user()->id);

                $user->first_connection = 2;
                $user->save();

                session()->flash('result', 'Votre mot de passe a bien été modifié');

                return redirect(route('modif.mdp'));
            }
            else if (auth()->user()->role == 1)
            {
                $user = User::findOrFail(auth()->user()->id);

                $user->first_connection = 2;
                $user->save();

                session()->flash('result', 'Votre mot de passe a bien été modifié');
                
                return redirect(route('modif.mdp'));
            }
            else
            {
                session()->flash('result', 'Votre mot de passe a bien été modifié');

                return redirect(route('modif.mdp'));
            }

        }  

        return view('modif_mdp');

    }

    public function projects_proceed()
    {

        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }

        $projects = Project::where('state', '=', 'en cours')->get();

        $pr = 'en cours';

        return view('show_projects',compact('projects','pr'));
        
    }

    public function projects_finish()
    {

        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }
        
        $projects = Project::where('state', '=', 'fini')->get();

        $pr = 'terminé';

        return view('show_projects',compact('projects','pr'));
    }

    public function projects_without_groups()
    {
        
        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }
        
        $projects = Project::whereNotIn('id', function ($query) {
            $query->select('project_id')
                  ->from('groups');
        })->get();

        $pr = 'sans groupe';

        return view('show_projects', compact('projects','pr'));
    }

    /*
    *****************************************************************************************************************************************Professeur***********************************************************************************************************************************************
    */


            // VIEW TASK

        public function tasks_proceed()
        {

        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }
        
            $currentDate = new DateTime();
            
            $teacher = auth()->user()->id;

            $tasks = Task::whereHas('group',function($group) use ($teacher)
                {
                    $group->whereHas('project',function($project) use ($teacher)
                    {
                        $project->where('user_id',$teacher);
                    });
                })->where('end_date','>',$currentDate)->whereDoesntHave('report')->get();

            $tk = 'en cours';

            return view('teacher_interface_shows.tasks',compact('tasks','tk'));
                
        }

        public function tasks_finish()
        {

        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }
        
            $teacher = auth()->user()->id;
            
            $tasks = Task::whereHas('group',function($group) use ($teacher)
                {
                    $group->whereHas('project',function($project) use ($teacher)
                    {
                        $project->where('user_id',$teacher);
                    });
                })->whereHas('report',function ($report)
                {
                    $report->whereDoesntHave('comment');
                })->get();

            $tk = 'sans commentaire';

            return view('teacher_interface_shows.tasks',compact('tasks','tk'));
                
        }

        public function tasks_expirate()
        {

        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }
        
            $currentDate = new DateTime();

            $teacher = auth()->user()->id;
            
            $tasks = Task::whereHas('group',function($group) use ($teacher)
                {
                    $group->whereHas('project',function($project) use ($teacher)
                    {
                        $project->where('user_id',$teacher);
                    });
                })->whereDoesntHave('report')->where('end_date','<',$currentDate)->get();

                $tk = 'non traité';

            return view('teacher_interface_shows.tasks',compact('tasks','tk'));
                
        }

        public function teacher_groups()
        {

        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }
        
            $teacher = auth()->user()->id;

            $groups = Group::whereHas('project',function ($project) use ($teacher)
                {
                    $project->where('user_id',$teacher);
                })
                ->with(['project','studentgroup' => function($studentgroup)
                {
                    $studentgroup->with('student');
                }])
                ->get();

                // dd($groups);

            

            return view('teacher_interface_shows.groups',compact('groups'));
                
        }

        public function teacher_project_proceed()
        {

        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }
        
            $teacher = auth()->user()->id;
            
            $projects = Project::where('user_id',$teacher)->where('state','en cours')->with(['teacher'])->get();

            $search = 1;

            return view('project_teacher.index',compact('projects','search'));
                
        }

        public function teacher_project_finish()
        {

        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }
        
            $teacher = auth()->user()->id;
            

            $projects = Project::where('user_id',$teacher)->where('state','fini')->with(['teacher'])->get();

            $search = 1;

            return view('project_teacher.index',compact('projects','search'));
                
        }

    /*
    *****************************************************************************************************************************************Etudiant***********************************************************************************************************************************************
    */

}
