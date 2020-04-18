@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Toutes les annonces') }}</div>
                @foreach($annonces as $annonce)
                    <div class="container">
                        <div class="row">
                            <div class="col-12 col-sm-8 col-md-6 col-lg-4">
                                <div class="card">
                                    @if(isset($annonce->image1))
                                    <img class="card-img" src="/uploads/annonce/{{$annonce->image1}}" alt="Photo">
                                    @else
                                    <img class="card-img" src="data:image/jpeg;base64,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" alt="Vans">
                                    @endif
                                    <div class="card-img-overlay d-flex justify-content-end">
                                    </div>
                                    <div class="card-body">
                                        <h4 class="card-title">{{$annonce->titre}}</h4>
                                        <h6 class="card-subtitle mb-2 text-muted">{{$annonce->type}}</h6>
                                        <p class="card-text">
                                            {{$annonce['description']}}</p>
                                        <div class="buy d-flex justify-content-between align-items-center">
                                            <div class="price text-success">
                                                <h5 class="mt-4">${{$annonce['prix']}}</h5>
                                        </div>
                                        <a href="{{url('/edit',$annonce->id)}}" class="btn btn-danger" style="z-index: 1000">Modifier</a>
                                    </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection