<template id="settings-template" xmlns="http://www.w3.org/1999/html">

    <section class="content-header">
        <h1>Settings</h1>
        <ol class="breadcrumb">
            <li><a href="#" v-link="{ path: '/' }"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Settings</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-xs-12 col-md-6 col-lg-4">

                <div class="box box-solid box-success">
                    <div class="box-header">
                        Account Settings
                    </div>
                    <div class="box-body">

                        <input type="hidden" name="id" value="{{ $currentUser->id }}" v-model="user_id">

                        <div class="alert alert-success" v-if="success.success[0]">
                            <i class="fa fa-check"></i> @{{ success.success[0] }}
                        </div>


                        <div class="form-group" v-bind:class="{ 'has-error': errors.email }">
                            <label for="">Your login/email</label>
                            <input class="form-control" placeholder="E-mail" autocomplete="off" type="email"
                                   name="email" value="{{ $currentUser->email }}" v-model="user.email">
                            <small class="error" v-if="errors.email">@{{ errors.email[0] }}</small>
                        </div>
                        <div class="form-group" v-bind:class="{ 'has-error': errors.password }">
                            <label for="">Old password</label>
                            <input class="form-control" placeholder="password" autocomplete="off" type="password"
                                   name="password" v-model="user.password">
                            <small class="error" v-if="errors.password">@{{ errors.password[0] }}</small>
                        </div>
                        <hr>
                        <div class="form-group" v-bind:class="{ 'has-error': errors.new_password }">
                            <label for="">New password (only if you want to change it)</label>
                            <input class="form-control" placeholder="password" autocomplete="off" type="password"
                                   name="new_password" v-model="user.new_password">
                            <small class="error" v-if="errors.new_password">@{{ errors.new_password[0] }}</small>

                        </div>
                        <div class="form-group" v-bind:class="{ 'has-error': errors.password_confirmation_pass }">
                            <label for="">Confirm password</label>
                            <input class="form-control" placeholder="password" autocomplete="off" type="password"
                                   name="new_password_confirmation" v-model="user.new_password_confirmation">
                            <small class="error" v-if="errors.password_confirmation_pass">@{{ errors.password_confirmation_pass[0] }}</small>
                        </div>
                        
                        

                    </div>
                    <div class="box-footer"><button class="btn btn-submit btn-success pull-right" @click.prevent="updateSettings()">Save changes</button></div>
                </div>

            </div>

            <div class="col-xs-12 col-md-6 col-lg-4">
                <div class="box box-solid box-primary">
                    <div class="box-header">
                        General Settings
                    </div>
                    <div class="box-body">
                        <div class="alert alert-success" v-if="success_general.success[0]">
                            <i class="fa fa-check"></i> @{{ success_general.success[0] }}
                        </div>

                        <div class="form-group">
                            <label for="">Weight Units</label>
                            <select name="user_general_weight_units" class="form-control" v-model="user.general_weight_units">
                              <option {{$currentUser && $currentUser->general_weight_units=='Ounces' ? 'selected' : ''}}>Ounces</option>
                              <option {{($currentUser && $currentUser->general_weight_units=='Pounds') || !$currentUser->general_weight_units ? 'selected' : ''}}>Pounds</option>
                              <option {{$currentUser && $currentUser->general_weight_units=='Pound/Ounces' ? 'selected' : ''}}>Pound/Ounces</option>
                              <option {{$currentUser && $currentUser->general_weight_units=='Grams' ? 'selected' : ''}}>Grams</option>
                              <option {{$currentUser && $currentUser->general_weight_units=='Kilograms' ? 'selected' : ''}}>Kilograms</option>
                            </select>

                        </div>

                        <hr>
                        <label for="">Breed Chain</label>
                        <div class="row">
                                
                                <div class="col-sm-12">

                                    <div class="tab-pane active" id="timeline">
                                        <!-- The timeline -->
                                      <ul class="timeline timeline-inverse">
                                          @foreach($currentUser->breedChainsOrdered as $bc)
                                          <li class="{{$bc->id}}">
                                              <i class="fa {{$bc->icon}}"></i>
                                              <div class="timeline-item">
                                                  <input type="hidden" v-model="user.breedchains.icon[{{$bc->id}}]" value="{{$bc->icon}}">
                                                  @if($bc->icon!='fa-venus-mars bg-blue' && $bc->icon!='fa-birthday-cake bg-green' && $bc->icon!='fa-balance-scale bg-yellow first-weight')
                                                      <span class="time"><button type="button" class="btn btn-danger btn-xs"  @click.prevent="removeChain('{{$bc->id}}')"><i class="fa fa-remove"></i></button></span>
                                                  @else
                                                      <span class="time" style="width: 40px"></span>
                                                  @endif
                                                  <span class="time"><input size="2" type="text" placeholder="0" value="{{$bc->days}}" v-model="user.breedchains.days[{{$bc->id}}]"> Days</span>
                                                  <h3 class="timeline-header"><input size="16" placeholder="Breed" type="text" value="{{$bc->name}}" v-model="user.breedchains.name[{{$bc->id}}]"></h3>
                                              </div>
                                          </li>
                                          @endforeach
                                      </ul>
                                  </div>
                            </div>
                            <div class="col-sm-10"><button class="btn btn-submit btn-defaukt" data-toggle="modal" href="#new_chain"><i class="fa fa-plus"></i> Add New</button></div>
                        </div>

                    </div>
                    <div class="box-footer"><button type="button" class="btn btn-submit btn-primary pull-right" @click.prevent="updateSettings('general')">Save changes</button></div>
                </div>
            </div>

            <div class="col-xs-12 col-md-6 col-lg-4">
                <div class="box box-solid box-info">
                    <div class="box-header">
                        Pedigree Settings
                    </div>
                    <div class="box-body">

                        <div class="alert alert-success" v-if="success_pedigree.success[0]">
                            <i class="fa fa-check"></i> @{{ success_pedigree.success[0] }}
                        </div>

                        <div class="form-group">
                            <label for="">Number of Generations</label>
                            <select class="form-control" name="units" v-model="user.pedigree_number_generations">
                              <option {{$currentUser && $currentUser->pedigree_number_generations=='2' ? 'selected' : ''}}>2</option>
                              <option {{($currentUser && $currentUser->pedigree_number_generations=='3') || !$currentUser->pedigree_number_generations ? 'selected' : ''}}>3</option>
                              <option {{$currentUser && $currentUser->pedigree_number_generations=='4' ? 'selected' : ''}}>4</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="">Rabbitry Information</label>
                            <textarea placeholder="Rabbitry Name" rows="3" class="form-control" v-model="user.pedigree_rabbitry_information" >{{ $currentUser->pedigree_rabbitry_information }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="">Logo</label>
                            <input type="file" v-model="avatar" name="avatar" v-el:avatar id="avatar">
                            @if($currentUser->pedigree_logo && File::exists(public_path() . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'pedigree' . DIRECTORY_SEPARATOR . $currentUser->pedigree_logo))
                            <img src="{{asset('media/pedigree/' . $currentUser->pedigree_logo)}}" height="50" class="pull-right" data-directory="{{asset('media/pedigree/')}}" id="logo_preview">
                            @endif
                        </div>

                    </div>

                    <div class="box-footer"><button type="button" class="btn btn-submit btn-info pull-right" @click.prevent="updateSettings('pedigree')">Save changes</button></div>
                </div>
            </div>

        </div>

    </section>




<!-- Modal -->
          <!-- modal -->
 <div class="modal" id="new_chain">
              <div class="modal-dialog">
                <div class="modal-content">
            <div class="modal-header bg-success">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">New Chain Item</h4>
    </div>
    <div class="modal-body">
                  <form class="form-horizontal row-paddings-compensation">



            <div class="row">
                <div class="form-group col-xs-7 col-sm-6">
                    <label class="col-sm-4 control-label">Name</label>
                    <div class="col-sm-8"><input class="form-control" placeholder="Enter ..." type="text" v-model="chainName"></div>
                </div>

                <div class="form-group col-xs-7 col-sm-6">
                    <label class="col-sm-4 control-label">Days</label>
                    <div class="col-sm-8"><input placeholder="Enter ..." class="form-control" type="number" v-model="chainDays"></div>
                </div>
            </div>

            <div class="row">


                <div class="form-group col-sm-12">
                    <label class="col-sm-2 control-label">Icon</label>
                    <div class="col-sm-8">
                        <div class="select-icon-of-task">
                            <label><input v-model="chainIcon" value="fa-cutlery bg-red" name="selecticon" type="radio" checked><i class="fa fa-cutlery icon-circle" v-bind:class="{'bg-red': iconBackground['bg-red']}"></i></label>
                            <label><input v-model="chainIcon" value="fa-venus-mars bg-blue" name="selecticon" type="radio"><i class="fa fa-venus-mars icon-circle" v-bind:class="{'bg-blue': iconBackground['bg-blue']}"></i></label>
                            <label><input v-model="chainIcon" value="fa-check bg-maroon" name="selecticon" type="radio"><i class="fa fa-check icon-circle" v-bind:class="{'bg-maroon': iconBackground['bg-maroon']}"></i></label>
                            <label><input v-model="chainIcon" value="fa-birthday-cake bg-green" name="selecticon" type="radio"><i class="fa fa-birthday-cake icon-circle" v-bind:class="{'bg-green': iconBackground['bg-green']}"></i></label>
                            <label><input v-model="chainIcon" value="fa-balance-scale bg-yellow" name="selecticon" type="radio"><i class="fa fa-balance-scale icon-circle" v-bind:class="{'bg-yellow': iconBackground['bg-yellow']}"></i></label>
                            <label><input v-model="chainIcon" value="fa-calendar bg-gray" name="selecticon" type="radio"><i class="fa fa-calendar icon-circle" v-bind:class="{'bg-black': iconBackground['fa-calendar']}"></i></label>
                            <label><input v-model="chainIcon" value="fa-heart bg-gray" name="selecticon" type="radio"><i class="fa fa-heart icon-circle" v-bind:class="{'bg-black': iconBackground['fa-heart']}"></i></label>
                            <label><input v-model="chainIcon" value="fa-asterisk bg-gray" name="selecticon" type="radio"><i class="fa fa-asterisk icon-circle" v-bind:class="{'bg-black': iconBackground['fa-asterisk']}"></i></label>
                            <label><input v-model="chainIcon" value="fa-bookmark bg-gray" name="selecticon" type="radio"><i class="fa fa-bookmark icon-circle" v-bind:class="{'bg-black': iconBackground['fa-bookmark']}"></i></label>
                            <label><input v-model="chainIcon" value="fa-eye bg-gray" name="selecticon" type="radio"><i class="fa fa-eye icon-circle" v-bind:class="{'bg-black': iconBackground['fa-eye']}"></i></label>
                            <label><input v-model="chainIcon" value="fa-flag bg-gray" name="selecticon" type="radio"><i class="fa fa-flag icon-circle" v-bind:class="{'bg-black': iconBackground['fa-flag']}"></i></label>
                            <label><input v-model="chainIcon" value="fa-medkit bg-gray" name="selecticon" type="radio"><i class="fa fa-medkit icon-circle" v-bind:class="{'bg-black': iconBackground['fa-medkit']}"></i></label>
                            <label><input v-model="chainIcon" value="fa-paw bg-gray" name="selecticon" type="radio"><i class="fa fa-paw icon-circle" v-bind:class="{'bg-black': iconBackground['fa-paw']}"></i></label>
                            <label><input v-model="chainIcon" value="fa-trophy bg-gray" name="selecticon" type="radio"><i class="fa fa-trophy icon-circle" v-bind:class="{'bg-black': iconBackground['fa-trophy']}"></i></label>
                            <label><input v-model="chainIcon" value="fa-inbox bg-purple" name="selecticon"  type="radio"><i class="fa fa-inbox icon-circle" v-bind:class="{'bg-purple': iconBackground['bg-purple']}"></i></label>
                        </div>

                    </div>
                </div>
            </div>


                  </form>
              </div>
                  <div class="modal-footer bg-success">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" @click.prevent="addChain()">Save changes</button>
                  </div>
                </div><!-- /.modal-content -->
              </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
<!-- End Modal -->


</template>