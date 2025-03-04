@extends('front.layout.layout')
@section('content')

        <!--====== App Content ======-->
        <div class="app-content">

            <!--====== Section 1 ======-->
            <div class="u-s-p-y-10">

                <!--====== Section Content ======-->
                <div class="section__content">
                    <div class="container">
                        <div class="breadcrumb">
                            <div class="breadcrumb__wrap">
                                <ul class="breadcrumb__list">
                                    <li class="has-separator">

                                        <a href="index.html">Home</a></li>
                                    <li class="is-marked">

                                        <a href="lost-password.html">Reset</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--====== End - Section 1 ======-->


            <!--====== Section 2 ======-->
            <div class="u-s-p-b-60">

                <!--====== Section Intro ======-->
                <div class="section__intro u-s-m-b-60">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="section__text-wrap">
                                    <h1 class="section__heading u-c-secondary">FORGOT PASSWORD?</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--====== End - Section Intro ======-->


                <!--====== Section Content ======-->
                <div class="section__content">
                    <div class="container">
                        <div class="row row--center">
                            <div class="col-lg-6 col-md-8 u-s-m-b-30">
                                <div class="l-f-o">
                                    <div class="l-f-o__pad-box">
                                        <h1 class="gl-h1">PASSWORD RESET</h1>

                                        <span class="gl-text u-s-m-b-30">Enter your email to reset your password.</span>
                                        <p id="reset-success"></p>
                                        <form class="l-f-o__form" id="resetPwdForm" action="javascript:;" method="post">@csrf
                                            <input type="hidden" name="code" value="{{ $code }}">
                                            <div class="u-s-m-b-30">

                                                <label class="gl-label" for="reset-password">NEW PASSWORD *</label>

                                                <input class="input-text input-text--primary-style" type="password" id="reset-password" name="password" placeholder="Enter New Password">
                                                <p class="reset-password"></p>
                                            </div>
                                            <div class="u-s-m-b-30">

                                                <button class="btn btn--e-transparent-brand-b-2" type="submit">SUBMIT</button></div>
                                            <div class="u-s-m-b-30">

                                                <a class="gl-link" href="{{ url('/user/login') }}">Back to Login</a></div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--====== End - Section Content ======-->
            </div>
            <!--====== End - Section 2 ======-->
        </div>
        <!--====== End - App Content ======-->

@endsection
