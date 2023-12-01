  @if (strlen(request()->search) <= 2)
      <h6>{{ __('system.fields.enter_more_char') }}</h6>
  @else
      @php
          $menus = [
              __('system.environment.menu') => route('restaurant.environment.setting'),
              __('system.themes.menu') => route('restaurant.themes.index'),
              __('system.qr_code.menu') => route('restaurant.create.QR'),
              __('system.dashboard.menu') => route('home'),
              __('system.users.menu') => route('restaurant.users.index'),
              __('system.restaurants.menu') => route('restaurant.restaurants.index'),
              __('system.food_categories.menu') => route('restaurant.food_categories.index'),
              __('system.foods.menu') => route('restaurant.foods.index'),
              __('system.languages.menu') => route('restaurant.languages.index'),
          ];
          $list = [
              __('system.languages.menu') => 'languages',
              __('system.restaurants.menu') => 'restaurants',
              __('system.users.menu') => 'users',
              __('system.foods.menu') => 'foods',
              __('system.food_categories.menu') => 'food_categories',
          ];
      @endphp

      @if (isset($search))
          @php($searchData = $search->groupByType())
          @php($find = 0)
      @endif

      @foreach ($menus as $key => $one)
          @if (Illuminate\Support\Str::contains(strtolower($key), explode(' ', strtolower(request()->search))) && !(isset($search) && isset($list[$key]) && isset($searchData[$list[$key]])))
              <div class="col-md-12 category-list">
                  <h4 class="mb-3"><a href="{{ $one }}"># {{ $key }} </a></h4>
              </div>
              @php($find++)
          @endif
      @endforeach

      @if (isset($search) && count($search) > 0)

          @foreach ($searchData as $key => $values)
              @if (count($values) == 0)
                  @continue
              @endif
              @if ($key == 'languages')
                  <div class="col-md-12 category-list">
                      <h4 class="mb-3"><a href="{{ route('restaurant.languages.index') }}"># {{ __('system.languages.menu') }} <small class="text-muted">{{ count($values) }}</small></a></h4>
                      <ul class=" mb-3 row">
                          @foreach ($values as $one)
                              <li class="col-lg-4 col-md-6 mb-2">
                                  <p>
                                      {{ $one->searchable->name }}
                                      <a href="{{ $one->url }}"><span class="badge fa-1x bg-primary"> {{ __('system.languages_data.edit.menu') }}</span></a>
                                  </p>
                              </li>
                          @endforeach
                      </ul>
                  </div>
              @elseif($key == 'restaurants')
                  <div class="col-md-12 category-list">
                      <h4 class="mb-3 "><a href="{{ route('restaurant.restaurants.index') }}"># {{ __('system.restaurants.menu') }} <small class="text-muted">{{ count($values) }}</small></a></h4>
                      <ul class=" mb-3 row">
                          @foreach ($values as $one)
                              <li class="list-group-item d-flex justify-content-between align-items-center col-lg-4 col-md-6 border-1">
                                  <div class="d-flex align-items-center">
                                      @if ($one->searchable->logo_url)
                                          <img src="{{ $one->searchable->logo_url }}" alt="" style="width: 45px; height: 45px" class="rounded-circle" />
                                      @else
                                          <div class="avatar-title bg-soft-primary text-primary font-size-18 m-0 rounded-circle font-weight-bold" style="width: 45px; height: 45px">
                                              {{ $one->searchable->logo_name }}
                                          </div>
                                      @endif
                                      <div class="ms-3">
                                          <p class="fw-bold mb-1 dash-text">{{ $one->searchable->name }}</p>
                                          <p class="text-muted mb-0 dash-text">{{ $one->searchable->phone_number }}</p>
                                      </div>
                                  </div>
                                  <div class="">
                                      @php($urls = explode('|', $one->url))
                                      <a href="{{ $urls[0] }}"><span class="badge fa-1x bg-secondary">{{ __('system.crud.show') }}</span></a>
                                      <a href="{{ $urls[1] }}"><span class="badge fa-1x bg-primary">{{ __('system.crud.edit') }}</span></a>

                                  </div>
                              </li>
                          @endforeach

                      </ul>
                  </div>
              @elseif($key == 'users')
                  <div class="col-md-12 category-list">
                      <h4 class="mb-3 "><a href="{{ route('restaurant.users.index') }}"># {{ __('system.users.menu') }} <small class="text-muted">{{ count($values) }}</small></a></h4>
                      <ul class=" mb-3 row">
                          @foreach ($values as $one)
                              <li class="list-group-item d-flex justify-content-between align-items-center col-lg-4 col-md-6 border-1">
                                  <div class="d-flex align-items-center">
                                      @if ($one->searchable->logo_url)
                                          <img src="{{ $one->searchable->logo_url }}" alt="" style="width: 45px; height: 45px" class="rounded-circle" />
                                      @else
                                          <div class="avatar-title bg-soft-primary text-primary font-size-18 m-0 rounded-circle font-weight-bold" style="width: 45px; height: 45px">
                                              {{ $one->searchable->logo_name }}
                                          </div>
                                      @endif
                                      <div class="ms-3">
                                          <p class="fw-bold mb-1 dash-text">{{ $one->searchable->name }}</p>
                                          <p class="text-muted mb-0 dash-text">{{ $one->searchable->email }}</p>
                                      </div>
                                  </div>
                                  <div class="">
                                      <a href="{{ $one->url }}"><span class="badge fa-1x bg-primary">{{ __('system.crud.edit') }}</span></a>

                                  </div>
                              </li>
                          @endforeach

                      </ul>
                  </div>
              @elseif($key == 'foods')
                  <div class="col-md-12 category-list">
                      <h4 class="mb-3 "><a href="{{ route('restaurant.foods.index') }}"># {{ __('system.foods.menu') }} <small class="text-muted">{{ count($values) }}</small></a></h4>
                      <ul class=" mb-3 row">
                          @foreach ($values as $one)
                              <li class="list-group-item d-flex justify-content-between align-items-center col-lg-4 col-md-6 border-1">
                                  <div class="d-flex align-items-center">
                                      @if ($one->searchable->food_image_url)
                                          <img src="{{ $one->searchable->food_image_url }}" alt="" style="width: 45px; height: 45px" class="rounded-circle" />
                                      @else
                                          <div class="avatar-title bg-soft-primary text-primary font-size-18 m-0 rounded-circle font-weight-bold" style="width: 45px; height: 45px">
                                              {{ $one->searchable->food_image_name }}
                                          </div>
                                      @endif
                                      <div class="ms-3">
                                          <p class="fw-bold mb-1 dash-text">{{ $one->searchable->local_lang_name }}</p>
                                          @if (app()->getLocale() != 'en')
                                              <p class="fw-bold mb-1 dash-text">{{ $one->searchable->name }}</p>
                                          @endif
                                      </div>
                                  </div>
                                  <div class="">
                                      @php($urls = explode('|', $one->url))
                                      <a href="{{ $urls[0] }}"><span class="badge fa-1x bg-secondary">{{ __('system.crud.show') }}</span></a>
                                      <a href="{{ $urls[1] }}"><span class="badge fa-1x bg-primary">{{ __('system.crud.edit') }}</span></a>
                                  </div>
                              </li>
                          @endforeach

                      </ul>
                  </div>
              @elseif($key == 'food_categories')
                  <div class="col-md-12 category-list">
                      <h4 class="mb-3 "><a href="{{ route('restaurant.food_categories.index') }}"># {{ __('system.food_categories.title') }} <small class="text-muted">{{ count($values) }}</small></a></h4>
                      <ul class=" mb-3 row">
                          @foreach ($values as $one)
                              <li class="list-group-item d-flex justify-content-between align-items-center col-lg-4 col-md-6 border-1">
                                  <div class="d-flex align-items-center">
                                      @if ($one->searchable->category_image_url)
                                          <img src="{{ $one->searchable->category_image_url }}" alt="" style="width: 45px; height: 45px" class="rounded-circle" />
                                      @else
                                          <div class="avatar-title bg-soft-primary text-primary font-size-18 m-0 rounded-circle font-weight-bold" style="width: 45px; height: 45px">
                                              {{ $one->searchable->category_image_name }}
                                          </div>
                                      @endif
                                      <div class="ms-3">
                                          <p class="fw-bold mb-1 dash-text">{{ $one->searchable->local_lang_name }}</p>
                                          @if (app()->getLocale() != 'en')
                                              <p class="fw-bold mb-1 dash-text">{{ $one->searchable->category_name }}</p>
                                          @endif
                                      </div>
                                  </div>
                                  <div class="">
                                      <a href="{{ $one->url }}"><span class="badge fa-1x bg-primary">{{ __('system.crud.edit') }}</span></a>

                                  </div>
                              </li>
                          @endforeach

                      </ul>
                  </div>
              @endif
          @endforeach
      @elseif($find == 0)
          <h6>{{ __('system.fields.no_data_found') }}</h6>


      @endif
  @endif
