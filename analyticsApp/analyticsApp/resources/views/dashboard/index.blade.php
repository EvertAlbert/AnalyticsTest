@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h2>Dashboard {{$data['message']}}</h2>
        <section class="row">
            <article class="col l12 m12 s12">
                <div class="card">
                    <div class="card-content center">
                        <a href="/dashboard/today">Today</a> -
                        <a href="/dashboard/yesterday">Yesterday</a> -
                        <a href="/dashboard/week">Last week</a> -
                        <a href="/dashboard/month">Last month</a> -
                        <a href="/dashboard/year">Last year</a> -
                        <a href="/dashboard/">All</a>
                    </div>
                </div>
            </article>
        </section>

    @if($data['totalVisitors'] === null)

        <section class="row">
            <h5 class="red-text center">No users registered {{$data['message']}}</h5>
        </section>

    @else
        <section class="row">
            <article class="col l3 m3 s6">
                <div class="card">
                    <div class="card-content">
                        <p><i class="fas fa-user"></i>
                            @if($data['totalVisitors'] !== null)
                                {{$data['totalVisitors']}}
                            @else
                                <span class="red-text">No users {{$data['message']}}</span>
                            @endif
                        </p>
                    </div>
                </div>
            </article>
            <article class="col l3 m3 s6">
                <div class="card">
                    <div class="card-content">
                        <!--most popular product-->
                        <p><i class="fas fa-fire-alt"></i>
                            @if($data['mostViewedProduct'] !== null)
                                {{$data['mostViewedProduct']}}
                            @else
                                <span class="red-text">No popular product {{$data['message']}}</span>
                            @endif
                        </p>
                    </div>
                </div>
            </article>
            <article class="col l3 m3 s6">
                <div class="card">
                    <div class="card-content">
                        <!--event counter-->
                        <p><i class="fas fa-bell"></i>
                            @if($data['eventAmount'] !== null)
                                {{$data['eventAmount']}}
                            @else
                                <span class="red-text">No events {{$data['message']}}</span>
                            @endif
                        </p>
                    </div>
                </div>
            </article>
            <article class="col l3 m3 s6">
                <div class="card">
                    <div class="card-content">
                    @if($data['rating'] !== null)
                        <!--user rating-->
                            @if($data['rating'] < 30)
                                <p id="rateBad"><i class="fas fa-frown"></i> {{$data['rating']}} %</p>
                            @elseif($data['rating']  >= 30 && $data['rating'] < 60)
                                <p id="rateMeh"><i class="fas fa-meh"></i> {{$data['rating']}} %</p>
                            @elseif($data['rating'] >= 60)
                                <p id="rateGood"><i class="fas fa-smile"></i> {{$data['rating']}} %</p>
                            @else
                                <p class="red-text">Something went wrong {{$data['message']}}</p>
                            @endif
                        @else
                            <p><i class="fas fa-question"></i><span
                                    class="red-text"> No data {{$data['message']}}</span>
                            </p>
                        @endif
                    </div>
                </div>
            </article>
        </section>

        <!--graphs-->
        <section class="row">
            <!--ages-->
            <article class="col l6 m6 s12">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title dashboard_cardTitle">User ages</span>
                        <canvas id="ageChart" class="dataChart dashboard_graph"></canvas>
                    </div>
                </div>
            </article>

            <!--langs-->
            <article class="col l6 m6 s12">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title dashboard_cardTitle">Users Languages</span>
                        <canvas id="langChart" class="dataChart dashboard_pieChart"></canvas>
                    </div>
                </div>
            </article>

            <!--most active times-->
            <article class="col l6 m6 s12">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title dashboard_cardTitle">Events per hour</span>
                        <canvas id="activityChart" class="dataChart dashboard_graph"></canvas>
                    </div>
                </div>
            </article>

            <!--product views-->
            <article class="col l6 m6 s12">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title dashboard_cardTitle">Products popularity</span>
                        <canvas id="productViewChart" class="dataChart dashboard_graph"></canvas>
                    </div>
                </div>
            </article>

        </section>
        <section>
            <!--product viewtime-->
            <article class="col l12 m12 s12">
                <section class="card">
                    <section class="card-content">
                        <span class="card-title dashboard_cardTitle">Average time spent per product</span>
                        <table id="productViewTimeCard">
                            @if(sizeof($data['productTimes']) > 0)
                                <tr>
                                    <th><b>ProductName</b></th>
                                    <th><b>Average time looked at</b></th>
                                </tr>
                                @foreach($data['productTimes'] as $productInfo)
                                    <tr>
                                        <td>{{$productInfo->name}}</td>
                                        <td>{{$productInfo->average_look_time}}</td>
                                    </tr>
                                @endforeach
                            @else
                                <p class="red-text">No information about products {{$data['message']}}</p>
                            @endif
                        </table>
                    </section>
                </section>
            </article>
            <!--page views-->
            <article class="col l12 m12 s12">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title dashboard_cardTitle">Page experiences</span>
                        <canvas id="pageVisitChart" class="dataChart dashboard_graph"></canvas>
                    </div>
                </div>
            </article>
            <!--user routes-->
            <article class="col l12 m12 s12">
                <section class="card">
                    <section class="card-content">
                        <span class="card-title dashboard_cardTitle">User Interactions</span>
                        <p><i class="far fa-clock"></i>
                            @if($data['averageVisitTime'] !== null)
                                {{$data['averageVisitTime']}}
                            @else
                                <span class="red-text">No data {{$data['message']}}</span>
                            @endif
                        </p>
                        <hr>
                        @if($data['userRoute'] !== null)
                            <section id="userNavField">
                                <table>
                                    <tr>
                                        <th><b>UUID</b></th>
                                        <th><b>Route</b></th>
                                    </tr>
                                    @foreach($data['userRoute'] as $userRoute)
                                        <tr>
                                            <td>{{$userRoute['user']}}</td>
                                            <td>
                                                @foreach($userRoute['route'] as $route)
                                                    @if($loop->last)
                                                        <a href="{{$route}}">{{$route}}</a>
                                                    @else
                                                        <a href="{{$route}}">{{$route}}</a> -
                                                    @endif

                                                @endforeach
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </section>
                        @else
                            <p class="red-text">No user navigation found {{$data['message']}}</p>
                        @endif

                    </section>
                </section>
            </article>
        </section>

        <script>
            const datePeriod = '{{$data['time']}}';
        </script>
        @include('inc.charts')
        <script src="/scripts/dashboard.js"></script> <!-- needs to be behind charts to be able to use chartfunctions-->
    @endif

@endsection
