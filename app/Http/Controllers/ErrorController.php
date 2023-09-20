<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ErrorController extends Controller
{
    public function error_admin()
    {
        // Déconnecter l'utilisateur
        Auth::logout();

        // Rediriger vers la page d'accueil avec un message d'erreur en tant que session flash

        session()->flash('error', 'Vous ne pouvez pas accéder à cette interface');

        return redirect()->route('accueil');
    }

    public function error_teacher()
    {
        // Déconnecter l'utilisateur
        Auth::logout();

        // Rediriger vers la page d'accueil avec un message d'erreur en tant que session flash

        session()->flash('error', 'Vous ne pouvez pas accéder à cette interface');

        return redirect()->route('accueil');
    }

    public function error_student()
    {
        // Déconnecter l'utilisateur
        Auth::logout();

        // Rediriger vers la page d'accueil avec un message d'erreur en tant que session flash

        session()->flash('error', 'Vous ne pouvez pas accéder à cette interface');

        return redirect()->route('accueil');
    }

    public function error_student()
    {
        // Déconnecter l'utilisateur
        Auth::logout();

        // Rediriger vers la page d'accueil avec un message d'erreur en tant que session flash

        session()->flash('error', 'Vous avez effectué une action non valide');

        return redirect()->route('accueil');
    }

}
