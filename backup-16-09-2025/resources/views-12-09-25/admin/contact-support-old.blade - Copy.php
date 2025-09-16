@include('admin.componants.header')

<body data-theme="light">
    <div id="body" class="theme-cyan">
        <div class="themesetting">
        </div>
        <div class="overlay"></div>
        <div id="wrapper">
            @include('admin.componants.navbar')
            @include('admin.componants.sidebar')
            <div id="main-content">
                <div class="container-fluid">
                   <!-- Page header section  -->
                    <div class="block-header">
                        <div class="row clearfix">
                            <div class="col-xl-5 col-md-5 col-sm-12">
                                <h1>Hi, {{  Auth()->user()->name }}!</h1>
                                <span>JustDo Chat,</span>
                            </div>            
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="chatapp_list">
                                    <ul class="nav nav-tabs2 mb-4 d-flex text-center">
                                        <li class="nav-item flex-fill"><a data-toggle="tab" class="nav-link active show" href="#chats-Users">Jobseekers</a></li>
                                        <li class="nav-item flex-fill"><a data-toggle="tab" class="nav-link" href="#chats-Groups">Recruiters</a></li>
                                        <li class="nav-item flex-fill mt-2"><a data-toggle="tab" class="nav-link mr-0" href="#chats-Contact">Mentors</a></li>
                                        <li class="nav-item flex-fill mt-2"><a data-toggle="tab" class="nav-link " href="#chats-Users">Assessors</a></li>
                                        <li class="nav-item flex-fill mt-2" ><a data-toggle="tab" class="nav-link" href="#chats-Groups">Coach</a></li>
                                        
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane vivify fadeIn active show" id="chats-Users">
                                            <!-- <div class="input-group mb-3">
                                                <input type="text" class="form-control" placeholder="Search...">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="icon-magnifier"></i></span>
                                                </div>
                                            </div> -->
                                            <ul class="right_chat list-unstyled mb-0 animation-li-delay">
                                                <li class="online">
                                                    <a href="javascript:void(0);" class="media">
                                                        <img class="media-object" src="../assets/images/xs/avatar1.jpg" alt="">
                                                        <div class="media-body">
                                                            <span class="name">Louis Henry <small class="text-muted  font-12">10 min</small></span>
                                                            <span class="message">How do you do?</span>
                                                            <span class="badge badge-outline status"></span>
                                                        </div>
                                                    </a>                            
                                                </li>
                                                <li class="online active">
                                                    <a href="javascript:void(0);" class="media">
                                                        <img class="media-object" src="../assets/images/xs/avatar2.jpg" alt="">
                                                        <div class="media-body">
                                                            <span class="name">Debra Stewart <small class="text-muted font-12">15 min</small></span>
                                                            <span class="message">I was wondering...</span>
                                                            <span class="badge badge-outline status"></span>
                                                        </div>
                                                    </a>                            
                                                </li>
                                                <li class="offline">
                                                    <a href="javascript:void(0);" class="media">
                                                        <img class="media-object" src="../assets/images/xs/avatar3.jpg" alt="">
                                                        <div class="media-body">
                                                            <span class="name">Lisa Garett <small class="text-muted font-12">18 min</small></span>
                                                            <span class="message">I've forgotten how it felt before</span>
                                                            <span class="badge badge-outline status"></span>
                                                        </div>
                                                    </a>                            
                                                </li>
                                                <li class="offline">
                                                    <a href="javascript:void(0);" class="media">
                                                        <img class="media-object" src="../assets/images/xs/avatar4.jpg" alt="">
                                                        <div class="media-body">
                                                            <span class="name">Folisise Chosielie <small class="text-muted font-12">23 min</small></span>
                                                            <span class="message">Wasup for the third time like...</span>
                                                            <span class="badge badge-outline status"></span>
                                                        </div>
                                                    </a>                            
                                                </li>
                                                <li class="online">
                                                    <a href="javascript:void(0);" class="media">
                                                        <img class="media-object" src="../assets/images/xs/avatar5.jpg" alt="">
                                                        <div class="media-body">
                                                            <span class="name">Marshall Nichols <small class="text-muted font-12">27 min</small></span>
                                                            <span class="message">But we’re probably gonna need a new carpet.</span>
                                                            <span class="badge badge-outline status"></span>
                                                        </div>
                                                    </a>                            
                                                </li>
                                                <li class="online">
                                                    <a href="javascript:void(0);" class="media">
                                                        <img class="media-object" src="../assets/images/xs/avatar6.jpg" alt="">
                                                        <div class="media-body">
                                                            <span class="name">Debra Stewart <small class="text-muted font-12">38 min</small></span>
                                                            <span class="message">It’s not that bad...</span>
                                                            <span class="badge badge-outline status"></span>
                                                        </div>
                                                    </a>                            
                                                </li>
                                                <li class="offline">
                                                    <a href="javascript:void(0);" class="media">
                                                        <img class="media-object" src="../assets/images/xs/avatar7.jpg" alt="">
                                                        <div class="media-body">
                                                            <span class="name">Lisa Garett <small class="text-muted font-12">45 min</small></span>
                                                            <span class="message">How do you do?</span>
                                                            <span class="badge badge-outline status"></span>
                                                        </div>
                                                    </a>                            
                                                </li>
                                            </ul>
                                        </div>


                                        <div class="tab-pane vivify fadeIn" id="chats-Groups">
                                            <!-- <div class="input-group mb-3">
                                                <input type="text" class="form-control" placeholder="Search...">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="icon-magnifier"></i></span>
                                                </div>
                                            </div> -->
                                            <ul class="right_chat list-unstyled mb-0 animation-li-delay">
                                                <li class="online">
                                                    <a href="javascript:void(0);" class="media">
                                                        <img class="media-object" src="../assets/images/xs/avatar5.jpg" alt="">
                                                        <div class="media-body">
                                                            <span class="name">PHP Groups<small class="text-muted font-12">10 min</small></span>
                                                            <span class="message">How do you do?</span>
                                                            <span class="badge badge-outline status"></span>
                                                        </div>
                                                    </a>                            
                                                </li>
                                                <li class="offline">
                                                    <a href="javascript:void(0);" class="media">
                                                        <img class="media-object" src="../assets/images/xs/avatar8.jpg" alt="">
                                                        <div class="media-body">
                                                            <span class="name">Family Groups <small class="text-muted font-12">18 min</small></span>
                                                            <span class="message">I've forgotten how it felt before</span>
                                                            <span class="badge badge-outline status"></span>
                                                        </div>
                                                    </a>                            
                                                </li>
                                                <li class="offline">
                                                    <a href="javascript:void(0);" class="media">
                                                        <img class="media-object" src="../assets/images/xs/avatar3.jpg" alt="">
                                                        <div class="media-body">
                                                            <span class="name">Friends holic <small class="text-muted font-12">23 min</small></span>
                                                            <span class="message">Wasup for the third time like...</span>
                                                            <span class="badge badge-outline status"></span>
                                                        </div>
                                                    </a>                            
                                                </li>
                                                <li class="offline">
                                                    <a href="javascript:void(0);" class="media">
                                                        <img class="media-object" src="../assets/images/xs/avatar1.jpg" alt="">
                                                        <div class="media-body">
                                                            <span class="name">CL City 2 <small class="text-muted font-12">45 min</small></span>
                                                            <span class="message">How do you do?</span>
                                                            <span class="badge badge-outline status"></span>
                                                        </div>
                                                    </a>                            
                                                </li>
                                            </ul>
                                        </div>


                                        <div class="tab-pane vivify fadeIn" id="chats-Contact">
                                            <!-- <div class="input-group mb-3">
                                                <input type="text" class="form-control" placeholder="Search...">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="icon-magnifier"></i></span>
                                                </div>
                                            </div> -->
                                            <ul class="right_chat list-unstyled mb-0 animation-li-delay">
                                                <li class="offline">
                                                    <a href="javascript:void(0);" class="media">
                                                        <img class="media-object" src="../assets/images/xs/avatar3.jpg" alt="">
                                                        <div class="media-body">
                                                            <span class="name">John Smith <small class="text-muted font-12"><i class="fa fa-star"></i></small></span>
                                                            <span class="message">johnsmith@info.com</span>
                                                            <span class="badge badge-outline status"></span>
                                                        </div>
                                                    </a>                            
                                                </li>
                                                <li class="online">
                                                    <a href="javascript:void(0);" class="media">
                                                        <img class="media-object" src="../assets/images/xs/avatar1.jpg" alt="">
                                                        <div class="media-body">
                                                            <span class="name">Debra Stewart <small class="text-muted font-12"><i class="fa fa-star"></i></small></span>
                                                            <span class="message">It’s not that bad...</span>
                                                            <span class="badge badge-outline status"></span>
                                                        </div>
                                                    </a>                            
                                                </li>
                                                <li class="offline">
                                                    <a href="javascript:void(0);" class="media">
                                                        <img class="media-object" src="../assets/images/xs/avatar2.jpg" alt="">
                                                        <div class="media-body">
                                                            <span class="name">Lisa Garett <small class="text-muted font-12"><i class="fa fa-star"></i></small></span>
                                                            <span class="message">eringonzales@info.com</span>
                                                            <span class="badge badge-outline status"></span>
                                                        </div>
                                                    </a>                            
                                                </li>
                                                <li class="online">
                                                    <a href="javascript:void(0);" class="media">
                                                        <img class="media-object" src="../assets/images/xs/avatar7.jpg" alt="">
                                                        <div class="media-body">
                                                            <span class="name">Louis Henry <small class="text-muted font-12"><i class="fa fa-star"></i></small></span>
                                                            <span class="message">susiewillis@info.com</span>
                                                            <span class="badge badge-outline status"></span>
                                                        </div>
                                                    </a>                            
                                                </li>
                                                <li class="online">
                                                    <a href="javascript:void(0);" class="media">
                                                        <img class="media-object" src="../assets/images/xs/avatar4.jpg" alt="">
                                                        <div class="media-body">
                                                            <span class="name">Debra Stewart <small class="text-muted font-12"><i class="fa fa-star"></i></small></span>
                                                            <span class="message">johnsmith@info.com</span>
                                                            <span class="badge badge-outline status"></span>
                                                        </div>
                                                    </a>                            
                                                </li>
                                            </ul>
                                        </div>


                                    </div>
                                </div>
                                <div class="chatapp_body">
                                    <div class="chat-header">
                                        <a href="javascript:void(0);" class="open_detail">
                                            <div class="media mb-0">
                                                <img class="rounded-circle w35" src="../assets/images/user.png" alt="">
                                                <div class="media-body mr-3 ml-3 text-muted">
                                                    <h6 class="m-0">Deborah Cox</h6>
                                                    <small>Webdeveloper</small>
                                                </div>
                                            </div>
                                        </a>

                                        <div>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-default hidden-xs"><i class="fa fa-file"></i></a>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-default"><i class="fa fa-image"></i></a>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-default"><i class="fa fa-video-camera"></i></a>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-default"><i class="fa fa-plus"></i></a>
                                        </div>
                                        
                                    </div>

                                    <div class="chat-history">
                                        <ul class="message_data">
                                            <li class="right clearfix">
                                                <img class="user_pix" src="../assets/images/xs/avatar5.jpg" alt="avatar">
                                                <div class="message">
                                                    <a href="javascript:void(0);" class="smily"><i class="fa fa-smile-o"></i></a>
                                                    <span>Hi Aiden, how are you?<br> How is the project coming along?</span>
                                                </div>
                                                <span class="data_time">10:12 AM, Today</span>
                                            </li>
                                            <li class="left clearfix">
                                                <img class="user_pix" src="../assets/images/user.png" alt="avatar">
                                                <div class="message">
                                                    <a href="javascript:void(0);" class="smily"><i class="fa fa-smile-o"></i></a>
                                                    <span>Are we meeting today?</span>
                                                    <div class="alert alert-primary mb-0 mt-2">
                                                        <i class="fa fa-file-word-o mr-2"></i> <span>finame12.doc</span>
                                                    </div>
                                                </div>
                                                <span class="data_time">10:12 AM, Today</span>
                                            </li>
                                            <li class="right clearfix">
                                                <img class="user_pix" src="../assets/images/xs/avatar5.jpg" alt="avatar">
                                                <div class="message">
                                                    <a href="javascript:void(0);" class="smily"><i class="fa fa-smile-o"></i></a>
                                                    <span>How is the project coming along?</span>
                                                </div>
                                                <span class="data_time">10:12 AM, Today</span>
                                            </li>
                                            <li class="divider clearfix">
                                                <span>yesterday</span>
                                            </li>
                                            <li class="left clearfix">
                                                <img class="user_pix" src="../assets/images/user.png" alt="avatar">
                                                <div class="message">
                                                    <a href="javascript:void(0);" class="smily"><i class="fa fa-smile-o"></i></a>
                                                    <span>Project has been already finished and I have<br> results to show you.</span>
                                                </div>
                                                <span class="data_time">10:16 AM, Today</span>
                                            </li>
                                            <li class="right clearfix">
                                                <img class="user_pix" src="../assets/images/xs/avatar5.jpg" alt="avatar">
                                                <div class="message">
                                                    <a href="javascript:void(0);" class="smily"><i class="fa fa-smile-o"></i></a>
                                                    <span>How is the project coming along?</span>
                                                    <div class="mt-2">
                                                        <img class="w100" src="../assets/images/image-gallery/1.jpg" alt="">
                                                        <img class="w100" src="../assets/images/image-gallery/2.jpg" alt="">
                                                    </div>
                                                </div>
                                                <span class="data_time">10:12 AM, Today</span>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="chat-message">
                                        <div class="form-group c_form_group mb-0">
                                            <textarea type="text" row="" class="form-control" placeholder="Enter text here..."></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="user_detail">
                                    <div class="text-center mb-4">
                                        <div class="profile-image"><img src="../assets/images/user.png" class="rounded-circle mb-3" alt=""></div>
                                        <h4 class="m-b-0">Louis Pierce</h4>
                                        <span>Washington, d.c.</span>
                                    </div>
                                    <div class="text-center">
                                        <small class="text-muted">Address: </small>
                                        <p>795 Folsom Ave, Suite 600 San Francisco, 94107</p>
                                        <small class="text-muted">Email address: </small>
                                        <p>louispierce@example.com</p>
                                        <small class="text-muted">Mobile: </small>
                                        <p>+ 202-222-2121</p>
                                        <small class="text-muted">Birth Date: </small>
                                        <p class="m-b-0">October 17th, 93</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.componants.footer')