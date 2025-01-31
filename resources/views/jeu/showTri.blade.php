<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="La Vik Team">
    <title>VikGames - {{$jeu->nom}}</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/product/">


    <!-- Bootstrap core CSS -->
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" crossorigin="anonymous">

    <!-- Favicons -->
    <link rel="icon" href="{{asset('images/favicon.ico')}}">
    <meta name="theme-color" content="#7952b3">

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>

    <!-- Custom styles for this template -->
    <link href="{{asset('css/product.css')}}" rel="stylesheet">
</head>
<body>

<header class="site-header sticky-top py-1">
    <nav class="container d-flex flex-column flex-md-row justify-content-between">
        <a class="py-2" href="#" aria-label="Product">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="d-block mx-auto" role="img" viewBox="0 0 24 24"><title>Product</title><circle cx="12" cy="12" r="10"/><path d="M14.31 8l5.74 9.94M9.69 8h11.48M7.38 12l5.74-9.94M9.69 16L3.95 6.06M14.31 16H2.83m13.79-4l-5.74 9.94"/></svg>
        </a>
        <a class="py-2 d-none d-md-inline-block" href="/dashboard">Accueil</a>
        <a class="py-2 d-none d-md-inline-block" href="/listeJeuxPages">Liste des jeux</a>
        <a class="py-2 d-none d-md-inline-block" href="/profil">Profil</a>
        <a class="py-2 d-none d-md-inline-block" href="rechercher">Rechercher</a>

        <div id="ProfileDropDown" class="rounded hidden shadow-md bg-white absolute pin-t mt-12 mr-1 pin-r">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="px-4 py-2 block  text-black hover:bg-grey-light" type="submit">Déconnexion</button>
            </form>
        </div>
    </nav>
</header>

<main>
    <div class="album py-5 bg-light">
        <div class="col-md-10 p-lg-10 mx-auto my-10">
            <div class="card-body">
                <p class="card-text">
                <ul>
                    <li> Jeu : {{$jeu->nom}}</li>
                    <li> Durée de partie : {{$jeu->duree}}</li>
                    <li> Nombre de joueurs : {{$jeu->nombre_joueurs}}</li>
                    <li> Description : {{$jeu->description}}</li>
                    <li> Photo : {{$jeu->url_media}}</li>
                    <li> Thème :

                        @foreach( \App\Models\Theme::all() as $theme)
                            @if ($jeu->theme_id == $theme->id)
                                {{ $theme->nom }}

                            @endif
                        @endforeach
                    </li>
                    <li> Langue :  {{$jeu->langue}}</li>
                    <li> Editeur :
                        @foreach( \App\Models\Editeur::all() as $editeur)
                            @if ($jeu->editeur_id == $editeur->id)
                                {{ $editeur->nom }}

                            @endif
                        @endforeach </li>
                    <li> Nombre de joueurs : {{$jeu->nombre_joueurs}} </li>
                    <li> Durée : {{$jeu->duree}} </li>

                </ul>
                </p>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group">
                        <a href="{{ URL::route('listeJeux')}}" class="btn btn-primary">Retour à la liste</a>
                        <a href="{{ URL::route('jeu_regles', $jeu->id)}}" class="btn btn-secondary">Voir les règles</a
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        <form name="form-create-jeu" method="post" action="{{ URL::route('commentaire.store') }}">
            @csrf
            <input type="hidden" value="{{$jeu->id}}" name="jeu">
            <div class="form-group">
                <label for="commentaire">Commentaire</label>
                <textarea name="commentaire" class="form-control" required="">
                    {{ old('commentaire') }}
                </textarea>
            </div>

            <div class="form-group">
                <label for="note">Note</label>
                <select name="note">
                    @for($note=0;$note<6;$note++)
                        <option value="{{$note}}">{{$note}}</option>
                    @endfor
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Soumettre</button>
        </form>
    </div>
    <span class="mini-titre">Commentaires :</span>

    <a href="{{ URL::route('jeu_show', $jeu->id) }}" class="btn btn-primary">Tri</a>

    </a>
    <style>
        .mini-titre{
            font-weight : bold;
            font-size : 30px;
        }
    </style>



    <ul>
        @foreach( \App\Models\Commentaire::all()->sortBy('date_com',SORT_REGULAR, true) as $com)

            @if ($jeu->id == $com->jeu_id)
                @foreach( \App\Models\User::all() as $user)
                    @if ($user->id == $com->user_id)
                        <li>  {{ $user->name }}</li>

                    @endif
                @endforeach

                <li>

                    {{ \App\Http\Services\DureeConvert::convertir(time() - strtotime($com->date_com ))   }}


                </li>



                <li>{{$com->commentaire}}</li>
                <li>Note : {{$com->note}}</li>
                </br>

            @endif
        @endforeach
    </ul>
    <span class="mini-titre">Statistiques :</span>
    <ul>

        <li style="color:rgb( {{ \App\Http\Services\DureeConvert::colorR( \App\Http\Services\DureeConvert::noteMoyenne($jeu) )   }},{{ \App\Http\Services\DureeConvert::colorG( \App\Http\Services\DureeConvert::noteMoyenne($jeu))   }},0)";> Note moyenne : {{ \App\Http\Services\DureeConvert::noteMoyenne($jeu)   }}</li>

        <li style="color:rgb( {{ \App\Http\Services\DureeConvert::colorR( \App\Http\Services\DureeConvert::noteMax($jeu) )   }},{{ \App\Http\Services\DureeConvert::colorG( \App\Http\Services\DureeConvert::noteMax($jeu))   }},0)";> Note maximale : {{ \App\Http\Services\DureeConvert::noteMax($jeu)   }}</li>
        <li style="color:rgb( {{ \App\Http\Services\DureeConvert::colorR( \App\Http\Services\DureeConvert::noteMin($jeu) )   }},{{ \App\Http\Services\DureeConvert::colorG( \App\Http\Services\DureeConvert::noteMin($jeu))   }},0)";> Note minimale : {{ \App\Http\Services\DureeConvert::noteMin($jeu)   }}</li>
        <li > Nombre de commentaires : {{ \App\Http\Services\DureeConvert::nbCom($jeu)   }}</li>
        <li> Nombre de commentaires (tous les jeux) : {{ \App\Http\Services\DureeConvert::nbComTotal()   }}</li>
        <li>Classement :<img src="{{asset('images/podium.png')}}" alt="podium" style="width:{{ \App\Http\Services\DureeConvert::taille(\App\Http\Services\DureeConvert::classement($jeu))  }}";"heigth:{{ \App\Http\Services\DureeConvert::taille(\App\Http\Services\DureeConvert::classement($jeu))  }}";>


            {{ \App\Http\Services\DureeConvert::classement($jeu)  }}</li>


        <span class="mini-titre">Informations tarifaires :</span>
    <ul>
        <li> Prix moyen : {{ $jeu->prixMoyen()  }}</li>
        <li> Prix maximal : {{ $jeu->prixMax()  }}</li>
        <li> Prix minimal : {{ $jeu->prixMin()  }}</li>
        <li> Nombre d'achats : {{ $jeu->nbAchat()  }}</li>
        <li> Nombre d'utilisateurs : {{\App\Http\Services\DureeConvert::nbUserTotal()  }}</li>

    </ul>


</main>

<footer class="container py-5">
    <div class="row">
        <div class="col-12 col-md">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="d-block mb-2" role="img" viewBox="0 0 24 24"><title>Product</title><circle cx="12" cy="12" r="10"/><path d="M14.31 8l5.74 9.94M9.69 8h11.48M7.38 12l5.74-9.94M9.69 16L3.95 6.06M14.31 16H2.83m13.79-4l-5.74 9.94"/></svg>
            <small class="d-block mb-3 text-muted">&copy; 2020</small>
        </div>
        <div class="col-6 col-md">
            <h5>La Vik Team</h5>
            <ul class="list-unstyled text-small">
                <li>Mathieu Maes</li>
                <li>Océane Pouilly</li>
                <li>Guillaume Vandeville</li>
                <li>Sasha Voiseux</li>
                <li>Germain Poloudenny</li>
                <li>Camille Plaska</li>
            </ul>
        </div>
    </div>
</footer>

<script src="{{asset('js/bootstrap.bundle.min.js')}}" crossorigin="anonymous"></script>

</body>
</html>


