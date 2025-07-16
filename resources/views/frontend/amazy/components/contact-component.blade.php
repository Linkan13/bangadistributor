
                                    <form class="form-area contact-form send_query_form" id="contactForm" action="#" name="#" enctype="multipart/form-data">
                                        @if(!empty($row) && !empty($form_data))
                                            @php
                                                $default_field = [];
                                                $custom_field = [];
                                                $custom_file = false;
                                            @endphp
                                            @foreach($form_data as $row)
                                                @php
                                                    if($row->type != 'header' && $row->type !='paragraph'){
                                                        if(property_exists($row,'className') && strpos($row->className, 'default-field') !== false){
                                                            $default_field[] = $row->name;
                                                        }else{
                                                            $custom_field[] = $row->name;
                                                            $custom_file  = true;
                                                        }
                                                        $required = property_exists($row,'required');
                                                        $type = property_exists($row,'subtype') ? $row->subtype : $row->type;
                                                        $placeholder = property_exists($row,'placeholder') ? $row->placeholder : $row->label;
                                                    }
                                                @endphp
                                                    @if($row->type =='header' || $row->type =='paragraph')
                                                        <div class="form-group">
                                                            <{{ $row->subtype }}>{{ $row->label }} </{{ $row->subtype }}>
                                                        </div>
                                                    @elseif($row->type == 'text' || $row->type == 'number' || $row->type == 'email' || $row->type == 'date')
                                                        <div class="col-xl-12">
                                                            <input {{$required ? 'required' :''}} name="{{$row->name}}" id="{{$row->name}}" placeholder="{{$row->label}}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{$row->label}}'" class="@error($row->name) is-invalid @enderror primary_line_input style4 mb_20" value="{{ old($row->name) }}" type="{{$type}}">
                                                            @error($row->name)
                                                                <span class="text-danger" >{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    @elseif($row->type=='select')
                                                        <div class="col-xl-12">
                                                            <select {{$required ? 'required' :''}} name="{{$row->name}}" id="{{$row->name}}" class="form-control amaz_select2 style2 wide mb_30">
                                                                @if($row->name == 'query_type')
                                                                    @foreach($QueryList as $key => $item)
                                                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                                                    @endforeach
                                                                @else
                                                                    @foreach($row->values as $value)
                                                                        <option value="{{$value->value}}" {{old($row->name) == $value->value? 'selected': ''}}>{{$value->label}}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                            <span class="text-danger">{{$errors->first($row->name)}}</span>
                                                        </div>
                                                    @elseif($row->type == 'date')
                                                        <div class="col-xl-12">
                                                            <input {{$required ? 'required' :''}} type="{{$type}}" id="datepicker" class="@error($row->name) form-control is-invalid @enderror" name="{{$row->name}}" value="{{ old($row->name) }}" placeholder="{{$placeholder}}">
                                                            @error($row->name)
                                                            <span class="text-danger" >{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    @elseif($row->type=='textarea')
                                                        <div class="col-xl-12">
                                                            <textarea class="form-control primary_line_textarea style4 mb_40" {{$required ? 'required' :''}} name="{{$row->name}}" placeholder="{{$placeholder}}" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Write Message here…'" id="{{$row->name}}">{{old($row->name)}}</textarea>
                                                            <span class="text-danger">{{$errors->first($row->name)}}</span>
                                                        </div>
                                                    @elseif($row->type=="radio-group")
                                                        <div class="col-xl-12">
                                                            <label for="">{{ $row->label }}</label>
                                                            <div class="address_type d-flex align-items-center gap_30 flex-wrap mb_5">
                                                                @foreach ($row->values as $value)
                                                                <label class="primary_checkbox style6 d-flex" >
                                                                    <input type="radio" name="{{ $row->name }}" value="{{ $value->value }}">
                                                                    <span class="checkmark mr_10"></span>
                                                                    <span class="label_name f_w_500">{{ $value->label }}</span>
                                                                </label>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @elseif($row->type=="checkbox-group")
                                                        <div class="col-xl-12 mb_20">
                                                            <label>{{@$row->label}}</label>
                                                            @foreach($row->values as $value)
                                                                <label class="primary_checkbox d-flex mb_30">
                                                                    <input type="checkbox"  name="{{ $row->name }}[]" value="{{ $value->value }}">
                                                                    <span class="checkmark mr_10"></span>
                                                                    <span class="label_name">{{$value->label}}</span>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    @elseif($row->type =='file')
                                                        <div class="col-xl-12 customer_img mb_20">
                                                            <input class="{{$custom_file ? 'custom_file' :''}} form-control" accept="image/*" type="{{$type}}" name="{{$row->name}}" id="{{$row->name}}" >
                                                        </div>
                                                    @elseif($row->type =='checkbox')
                                                        <div class="col-md-12 mb-15">
                                                            <div class="checkbox">
                                                                <label class="cs_checkbox">
                                                                    <input id="policyCheck" type="checkbox" checked>
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                <p>{{$row->label}}</p>
                                                            </div>
                                                        </div>
                                                    @endif

                                                @endforeach

                                                <input type="hidden" name="custom_field" value="{{json_encode($custom_field)}}">

                                            @else
                                            <input type="hidden" name="custom_field" value="{{ json_encode(['phone','district']) }}">
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <input name="name" id="name" placeholder="{{__('defaultTheme.enter_name')}}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{__('defaultTheme.enter_name')}}'" class="primary_line_input style4 mb_20" type="text">
                                                    <span class="text-danger"  id="error_name"></span>
                                                </div>

                                                <div class="col-xl-12">
                                                    <input name="email" id="email" placeholder="{{__('defaultTheme.enter_email_address')}}"  onfocus="this.placeholder = ''" onblur="this.placeholder = '{{__('defaultTheme.enter_email_address')}}'" class="primary_line_input style4 mb_20" type="email">
                                                    <span class="text-danger"  id="error_email"></span>
                                                </div>

                                                <div class="col-xl-12">
                                                    <input name="phone" id="phone" placeholder="{{__('defaultTheme.phone_number')}}"  onfocus="this.placeholder = ''" onblur="this.placeholder = '{{__('defaultTheme.phone_number')}}'" class="primary_line_input style4 mb_20" type="phone">
                                                    <span class="text-danger"  id="error_phone_number"></span>
                                                </div>


                                                <div class="col-xl-12">
                                                    <select name="query_type" id="query_type" class="amaz_select2 style2 wide mb_30 nc_select" >
                                                        @foreach($QueryList as $key => $item)
                                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <span class="text-danger" id="error_query_type"></span>

                                                <div class="col-xl-12">
                                                    <textarea class="primary_line_textarea style4 mb_40" id="message" name="message" placeholder="{{__('defaultTheme.write_messages')}}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{__('defaultTheme.write_messages')}}'"></textarea>
                                                    <span class="text-danger"  id="error_message"></span>
                                                </div>
                                            @endif
                                            @if(env('NOCAPTCHA_FOR_CONTACT') == "true")
                                            <div class="col-12 mb_20">
                                                @if(env('NOCAPTCHA_INVISIBLE') != "true")
                                                    <div class="g-recaptcha" data-callback="callback" data-sitekey="{{env('NOCAPTCHA_SITEKEY')}}"></div>
                                                @else
                                                    <div class="g-recaptcha"
                                                        data-sitekey="{{env('NOCAPTCHA_SITEKEY')}}"
                                                        data-callback="onSubmit"
                                                        data-size="invisible">
                                                    </div>
                                                @endif
                                                <span class="text-danger" id="error_g_recaptcha"></span>
                                            </div>
                                            @endif
                                                <div class="col-lg-12 text-right send_query_btn">
                                                    <div class="alert-msg"></div>
                                                    <button  @if(env('NOCAPTCHA_FOR_CONTACT') == "true") style="margin-top: 80px !important;" @endif type="submit" id="contactBtn" class="amaz_primary_btn style2 submit-btn text-center f_w_700 text-uppercase rounded-0 w-100 btn_1" >{{__('defaultTheme.send_message')}}</button>
                                                </div>
                                        </div>

                                    </form>