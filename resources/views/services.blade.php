@extends('layout.app')
@section('title')
    Наши услуги
@endsection
@section('main')
    <div class="container" id="Services">
        <div class="min-container">

            <div class="mb-1">
                <div class="mb-1">
                    <input type="text" name="search" id="search"
                           class="form-settings search-form" placeholder="Поиск" v-model="search"
                           :class="{
                               'p-light': theme === 'light',
                               'p-night': theme !== 'light',
                               'search-form-light': theme === 'light',
                               'search-form-night': theme !== 'light'
                           }">
                </div>

                <div class="button-end">
                    <select style="width: 40%" v-model="category_filter_id" name="category_filter" id="category_filter"
                            class="form-settings select-form"
                            :class="{
                                    'p-light': theme === 'light',
                                    'p-night': theme !== 'light',
                                    'options-light': theme === 'light',
                                    'options-night': theme !== 'light',
                                    'select-form-light': theme === 'light',
                                    'select-form-night': theme !== 'light'
                                }">
                        <option value="0">Все</option>
                        <option v-for="category in categories" :value="category.id">@{{ category.title }}</option>
                    </select>
                </div>
            </div>

            @guest()
                <div class="mb-2" :class="theme === 'light' ? 'p-light':'p-night'">
                    <p style="text-align: center">Чтобы записаться на услугу, <a href="{{route('authorization')}}" class="link-a">авторизуйтесь!</a></p>
                </div>
            @endguest

            <div class="mb-3" :class="theme === 'light' ? 'p-light':'p-night'">
                <div class="list mb-1" v-if="services.length > 0">
                    <table class="table-list">
                        <thead>
                        <tr>
                            <td rowspan="2" style="width: 40%"><span>Процедура</span></td>
                            <td><span>Цена</span></td>
                        </tr>
                        <tr>
                            <td style="width:15%" v-for="qualification in qualifications"><span>@{{ qualification.title }}</span></td>
                        </tr>
                        </thead>
                        <tbody>
                        <template v-for="(service, index) in filteredServices">
                            <tr v-if="index < 5 || service_open_short === true">
                                <td>@{{ service.title }}</td>
                                <td v-for="qualification in qualifications" :key="qualification.id">
                                    <p v-if="hasQualification(service, qualification.id)">
                                        @{{ getQualificationPrice(service, qualification.id) }}
                                    </p>
                                    <p v-else></p>
                                </td>
                                @auth()
                                <td style="width:15%">
                                    <button class="btn-empty p-btn-empty" @click="store_application_modal(service)" type="button">Записаться</button>
                                </td>
                                @endauth
                            </tr>
                        </template>
                        </tbody>
                    </table>
                </div>

                <div class="button-end" v-if="filteredServices.length > 5">
                    <button class="full-btn" @click="service_pagination"
                            v-if="service_open_short === false && filteredServices.length > 5">ЕЩЁ...</button>
                    <button class="full-btn" @click="service_pagination"
                            v-if="service_open_short === true && filteredServices.length > 5">Свернуть</button>
                </div>

                <div v-if="services.length == 0">
                    <p style="text-align: center">Пока не добавлено ни одной услуги</p>
                </div>

                <div v-if="filteredServices.length == 0 && services.length > 0">
                    <p style="text-align: center">Ничего не найдено</p>
                </div>
            </div>
        </div>

        @auth()
        {{--        save-application--}}
        <div class="modal-container" id="createApplicationModal">
            <div class="modal-inside">
                <form id="StoreApplicationForm" @submit.prevent="storeApplication">
                    <div class="title">
                        <h1>Запись на услугу <span style="color: #BC13FE">@{{ service_obj.title }}</span></h1>
                    </div>

                    <div :class="message_create_applications_error ? 'alert-error':''">
                        @{{ message_create_applications_error }}
                    </div>

                    <p class="mb-03" v-if="service_obj.description != ''">
                        @{{ service_obj.description }}
                    </p>

                    <div class="mb-03">
                        <select v-model="master_id" name="master" id="master"
                                class="form-settings select-form select-button select-form-light" @change="getMasterCalendars">
                            <option value="0" selected>Мастер</option>
                            <option v-for="master in service_obj.masters" :value="master.id">
                                @{{ master.name }}, @{{ master.qualification.title }}
                            </option>
                        </select>
                    </div>

                    <div class="mb-03" v-if="master_id != 0">
                        <select v-model="month_id" name="month" id="month"
                                class="form-settings select-form select-button select-form-light" @change="getInfoMonth">
                            <option value="0" selected>Месяц</option>
                            <option v-for="month in months" :value="month.id">
                                @{{ month.name_month }}, @{{ month.year }}
                            </option>
                        </select>
                    </div>

                    <table class="mb-03 calendar" v-if="month_id != 0">
                        <thead>
                        <tr>
                            <td class="weekday"><p>Пн</p></td>
                            <td class="weekday"><p>Вт</p></td>
                            <td class="weekday"><p>Ср</p></td>
                            <td class="weekday"><p>Чт</p></td>
                            <td class="weekday"><p>Пт</p></td>
                            <td class="weekends"><p>Сб</p></td>
                            <td class="weekends"><p>Вс</p></td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td v-for="skip in selected_mouth.skipDays" :key="skip"></td>
                            <td v-for="day in (7 - selected_mouth.skipDays)">
                                <button :id="`${day}`" type="button" class="btn-calendar"
                                        @click="getFocusBtn(day)"
                                        :class="(btnHidden(day) ? 'btn-calendar-hidden-light':'') || (btnColor(day) ? 'btn-calendar-date-yes':'btn-calendar-hidden-light')">
                                    @{{ day }}</button>
                            </td>
                        </tr>
                        <tr v-for="(week, index_week) in (Math.floor(selected_mouth.days / 7))">
                            <td v-for="day in 7" :key="(7 - selected_mouth.skipDays) + day" v-if="index_week === 0">
                                <button name="day[]" :id="`${(7 - selected_mouth.skipDays) + day}`" type="button" class="btn-calendar"
                                        @click="getFocusBtn((7 - selected_mouth.skipDays) + day)"
                                        :class="(btnHidden((7 - selected_mouth.skipDays) + day) ? 'btn-calendar-hidden-light':'btn-calendar-hidden-light')
                                        || (btnColor((7 - selected_mouth.skipDays) + day) ? 'btn-calendar-date-yes':'')">
                                    @{{ (7 - selected_mouth.skipDays) + day }}</button>
                            </td>
                            <td v-for="day in 7" :key="((7 - selected_mouth.skipDays) + (index_week * 7)) + day" v-if="(index_week !== 0)">
                                <button name="day[]" :id="`${(7 - selected_mouth.skipDays) + (index_week * 7) + day}`"
                                        type="button" class="btn-calendar"
                                        @click="getFocusBtn((7 - selected_mouth.skipDays) + (index_week * 7) + day)"
                                        v-if="(((7 - selected_mouth.skipDays) + (index_week * 7) + day) <= selected_mouth.days)"
                                        :class="(btnHidden((7 - selected_mouth.skipDays) + (index_week * 7) + day) ? 'btn-calendar-hidden-light':'')
                                        || (btnColor((7 - selected_mouth.skipDays) + (index_week * 7) + day) ? 'btn-calendar-date-yes':'btn-calendar-hidden-light')  ">
                                    @{{ (7 - selected_mouth.skipDays) + (index_week * 7) + day }}</button>
                            </td>
                        </tr>

                        <tr class="" v-if="(Math.floor(selected_mouth.days / 7) * 7) + (7 - selected_mouth.skipDays) < selected_mouth.days">
                            <td v-for="day in 7" :key="(7 - selected_mouth.skipDays) + (Math.floor(selected_mouth.days / 7) * 7) + day">
                                <button name="day[]" :id="`${(7 - selected_mouth.skipDays) + (Math.floor(selected_mouth.days / 7) * 7) + day}`"
                                        type="button" class="btn-calendar"
                                        @click="getFocusBtn((7 - selected_mouth.skipDays) + (Math.floor(selected_mouth.days / 7) * 7) + day)"
                                        v-if="(7 - selected_mouth.skipDays) + (Math.floor(selected_mouth.days / 7) * 7) + day <= selected_mouth.days"
                                        :class="(btnHidden((7 - selected_mouth.skipDays) + (Math.floor(selected_mouth.days / 7) * 7) + day) ? 'btn-calendar-hidden-light':'')
                                        || (btnColor((7 - selected_mouth.skipDays) + (Math.floor(selected_mouth.days / 7) * 7) + day) ? 'btn-calendar-date-yes':'btn-calendar-hidden-light')">
                                    @{{ (7 - selected_mouth.skipDays) + (Math.floor(selected_mouth.days / 7) * 7) + day }}</button>
                            </td>
                        </tr>

                        </tbody>
                    </table>

                    <div class="times" v-if="date_times != []">
                        <button type="button" class="btn-empty" style="margin: 0 0.3rem 0.3rem 0"
                                v-for="time in date_times" :id="time[0]" @click="getTime(time[0])">
                            @{{ time[0] }}</button>
                    </div>

                    <div class="mb-1">
                        <input v-model="tel" type="tel" name="tel" id="tel" class="form-settings" placeholder="Номер телефона"
                               :class="errors_applications.tel ? 'is-invalid':''">

                        <div class="invalid-feedback" v-for="error in errors_applications.tel">
                            @{{ error }}
                        </div>
                    </div>

                    @if(\Illuminate\Support\Facades\Auth::user())
                        <div class="mb-1 price-block" v-if="master_id != 0">
                            <p style="margin: 0;"><span>Цена без скидки:</span> @{{ qualification_price_duration.price }} ₽</p>
                            <p style="margin: 0;"><span>Скидка:</span> @{{ user.discount }}%</p>
                            <p style="margin: 0;"><span>Итого:</span> @{{ qualification_price_duration.price - (qualification_price_duration.price * user.discount / 100) }} ₽</p>
                            <p style="margin: 0;"><span>Длительность:</span> @{{ duration_format(qualification_price_duration.duration) }}</p>
                        </div>

                        <div class="mb-1 price-block" v-if="master_id == 0">
                            <p style="margin: 0;"><span>Цена без скидки:</span> 0 ₽</p>
                            <p style="margin: 0;"><span>Скидка:</span> @{{ user.discount }}%</p>
                            <p style="margin: 0;"><span>Итого:</span> 0 ₽</p>
                            <p style="margin: 0;"><span>Длительность:</span> 0 минут</p>
                        </div>
                    @endif

                    <div class="button-end">
                        <button class="secondary-btn" type="button" @click="store_application_modal">Отмена</button>
                        <button class="btn-full-form">Записаться</button>
                    </div>
                </form>
            </div>
        </div>
        @endauth
    </div>

    @include('layout.footer')

    <script>
        const App = {
            data() {
                return {

                    errors_applications: [],
                    message_create_applications_error: '',
                    message_create_applications: '',

                    masters: [],
                    categories: [],
                    services: [],
                    qualifications: [],
                    user: '',

                    service_obj: {
                        id: 0,
                        title: '',
                        masters: [],
                        months: [],
                        qualifications: []
                    },
                    master_id: 0,
                    master: [],
                    months: [],
                    selected_mouth: '',
                    calendar: '',
                    month_id: 0,
                    day: '',
                    focus_btn_id: 0,
                    date_times: [],
                    time: '',

                    qualification_price_duration: {
                        qualification_id: 0,
                        price: 0,
                        duration: 0,
                    },

                    tel: '',
                    category_filter_id: 0,
                    search: '',

                    theme: localStorage.getItem('theme') || 'light',
                    service_open_short: false,

                }
            },

            methods: {

                // get-methods
                async getMasters() {
                    const response = await fetch('{{route('getActiveMasters')}}');
                    this.masters = await response.json();
                    this.masters = this.masters.map(master => {
                        let master_calendars = master.calendars.map(calendar => {
                            let calendar_dates = calendar.dates.map(day => {
                                let time_array = day[Object.keys(day)[0]].map(time => {
                                    if (time[1] === 'Свободно') return time;
                                })
                                for (let i = 0; i < time_array.length; i++) {
                                    if(time_array[i] === undefined) {
                                        time_array.splice(i, 1);
                                        i--;
                                    }
                                }
                                day[Object.keys(day)] = time_array;
                                return day;
                            })
                            for (let i = 0; i < calendar_dates.length; i++) {
                                if(calendar_dates[i][Object.keys(calendar_dates[i])[0]].length === 0) {
                                    calendar_dates.splice(i, 1);
                                    i--;
                                }
                            }
                            calendar.dates = calendar_dates;
                            calendar.dates.sort((date1, date2) => Number(Object.keys(date1)[0]) - Number(Object.keys(date2)[0]));
                            return calendar;
                        });

                        for (let i = 0; i < master_calendars.length; i++) {
                            if(master_calendars[i].dates.length === 0) {
                                master_calendars.splice(i, 1);
                                i--;
                            }
                        }
                        master.calendars = master_calendars;
                        return master;
                    });
                    for (let i = 0; i < this.masters.length; i++) {
                        if(this.masters[i].calendars.length === 0) {
                            this.masters.splice(i, 1);
                            i--;
                        }
                    }
                },
                async getQualifications() {
                    const response = await fetch('{{route('getQualifications')}}');
                    this.qualifications = await response.json();
                },
                async getServices() {
                    const response = await fetch('{{route('getServices')}}');
                    this.services = await response.json();
                },
                async getUser() {
                    const response = await fetch('{{route('getUser')}}');
                    this.user = await response.json();
                },
                async getCategories() {
                    const response = await fetch('{{route('getCategories')}}');
                    this.categories = await response.json();
                },
                getMonths() {
                    let new_months = [];
                    this.months.map(month => {
                        let days = new Date(month.year, month.month_number + 1, 0).getDate();
                        let skipDays = new Date(month.year, month.month_number - 1, 1).getDay() - 1;
                        if (skipDays === -1) {
                            skipDays = 6;
                        }
                        new_months.push({
                            id: month.id,
                            number_month: month.month_number,
                            name_month: month.month_name,
                            year: month.year,
                            days: days,
                            skipDays: skipDays
                        });
                    });
                    this.months = new_months;
                },


                //store-methods
                async storeApplication() {
                    let form = document.getElementById('StoreApplicationForm');
                    let data = new FormData(form);
                    data.append('service_id', this.service_obj.id);
                    data.append('day', this.focus_btn_id);
                    data.append('time', this.time);
                    const response = await fetch('{{route('saveApplication')}}', {
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        },
                        body:data
                    });

                    if(response.status === 200) {
                        window.location = response.url;
                    }

                    if(response.status === 201) {
                        this.store_application_modal();
                        this.message_create_applications = await response.json();
                        this.message_create_applications_error = '';
                        this.errors_applications = [];
                        form.reset();
                        this.month_id = 0;
                        this.master = '';
                        this.date_times = [];
                        this.time = 0;
                        this.focus_btn_id = 0;
                        this.getMasters();
                        this.application_success_modal();
                    }

                    if(response.status === 400) {
                        this.errors_applications = await response.json();
                        this.message_create_applications = '';
                        this.message_create_applications_error = '';
                    }

                    if(response.status === 422) {
                        this.message_create_applications_error = await response.json();
                        this.message_create_applications = '';
                        this.errors_applications = [];
                    }

                },

                //modal
                store_application_modal(service) {
                    document.getElementById('createApplicationModal').classList.toggle('modal-container-opacity');
                    this.service_obj.id = 0;
                    this.service_obj.title = '';
                    this.service_obj.masters = [];
                    this.service_obj.description = '';
                    this.service_obj.qualifications = [];
                    this.master_id = 0;
                    this.month_id = 0;
                    this.date_times = [];
                        this.tel = '';
                    if (service && service.id) {
                        this.service_obj.id = service.id;
                        this.service_obj.title = service.title;
                        this.service_obj.description = service.description;
                        this.service_obj.masters = this.masters.map(master => {
                            for (let i = 0; i < service.masters.length; i++) {
                                if (master.id === service.masters[i].id) {
                                    return master;
                                }
                            }
                        });
                        for (let i = 0; i < this.service_obj.masters.length; i++) {
                            if (this.service_obj.masters[i] === undefined) {
                                this.service_obj.masters.splice(i, 1);
                                i--;
                            }
                        }
                        this.service_obj.qualifications = service.qualifications;
                    }

                },

                hasQualification(service, qualificationId) {
                    return service.qualifications.some(q => q.id === qualificationId);
                },
                getQualificationPrice(service, qualificationId) {
                    const qualification = service.qualifications.find(q => q.id === qualificationId);
                    return qualification ? qualification.price : '';
                },

                getMasterCalendars() {
                    this.month_id = 0;
                    this.date_times = [];
                    this.focus_btn_id = 0;
                    this.time = 0;
                    if (this.master_id != 0) {
                        this.master = this.masters.find(master => master.id === this.master_id);

                        this.qualification_price_duration.qualification_id = this.master.qualification_id;

                        this.qualification_price_duration.price = this.service_obj.qualifications.find(qualification =>
                            qualification.id === this.qualification_price_duration.qualification_id).price;

                        this.qualification_price_duration.duration = this.service_obj.qualifications.find(qualification =>
                            qualification.id === this.qualification_price_duration.qualification_id).duration;

                        let master_calendars = this.master.calendars.map(calendar => {
                            let calendar_dates = calendar.dates.map(day => {
                                let time_array = day[Object.keys(day)[0]].map(time => {
                                    if (time[1] === 'Свободно') return time;
                                })
                                for (let i = 0; i < time_array.length; i++) {
                                    if(time_array[i] === undefined) {
                                        time_array.splice(i, 1);
                                        i--;
                                    }
                                }
                                day[Object.keys(day)] = time_array;
                                return day;
                            })
                            for (let i = 0; i < calendar_dates.length; i++) {
                                if(calendar_dates[i][Object.keys(calendar_dates[i])[0]].length === 0) {
                                    calendar_dates.splice(i, 1);
                                    i--;
                                }
                            }
                            calendar.dates = calendar_dates;
                            calendar.dates.sort((date1, date2) => Number(Object.keys(date1)[0]) - Number(Object.keys(date2)[0]));
                            return calendar;
                        });

                        for (let i = 0; i < master_calendars.length; i++) {
                            if(master_calendars[i].dates.length === 0) {
                                master_calendars.splice(i, 1);
                                i--;
                            }
                        }
                        this.months = master_calendars;
                        this.master.calendars = master_calendars;
                    }
                    this.getMonths();
                },
                getInfoMonth() {
                    this.date_times = [];
                    this.time = 0;
                    if(this.focus_btn_id != 0) {
                        document.getElementById(this.focus_btn_id).classList.toggle('btn-calendar-focus');
                    }
                    this.focus_btn_id = 0;
                    if (this.month_id != 0) {
                        this.selected_mouth = this.months.find(month => month.id === this.month_id);
                        this.calendar = this.master.calendars.find(calendar => calendar.id === this.month_id);
                    } else {
                        this.selected_mouth = '';
                        this.calendar = '';
                    }
                },
                btnHidden(day) {
                    let today = new Date();
                    let date = new Date(this.selected_mouth.year + '-' + this.selected_mouth.number_month + '-' + day);
                    if (today.getTime() > date.getTime()) return true
                },
                getFocusBtn(id) {
                    if(this.focus_btn_id !== 0) {
                        document.getElementById(this.focus_btn_id).classList.toggle('btn-calendar-focus');
                    }
                    this.focus_btn_id = id;
                    document.getElementById(this.focus_btn_id).classList.toggle('btn-calendar-focus');
                    this.getDateTimes(id);
                },
                btnColor(day) {
                    if (this.calendar !== []) {
                        for (let i = 0; i < this.calendar.dates.length; i++) {
                            let date = this.calendar.dates[i];

                            if (date.hasOwnProperty(day)) return true;
                        }
                    }
                    return false;
                },
                getDateTimes(day) {
                    if (this.selected_mouth !== '') {
                        for (let i = 0; i < this.calendar.dates.length; i++) {
                            let date = this.calendar.dates[i];

                            if (date.hasOwnProperty(day)) {
                                this.date_times = date[day];
                                this.date_times = this.date_times.filter(item => item[1] === 'Свободно');
                                if(this.date_times.length === 0) {
                                    this.date_times = [''];
                                }
                                break;
                            } else {
                                this.date_times = [];
                            }
                        }
                    }
                },
                getTime(time) {
                    if (this.time !== 0) {
                        document.getElementById(this.time).classList.remove('btn-empty-active');
                    }
                    document.getElementById(time).classList.add('btn-empty-active');
                    this.time = time;
                },

                //pagination
                service_pagination() {
                    if (this.service_open_short === false) this.service_open_short = true;
                    else this.service_open_short = false;
                },

                duration_format(duration) {
                    let hours = Math.floor(duration / 60);
                    let minutes = duration - (Math.floor(duration / 60) * 60);
                    return `${hours} ч. ${minutes} мин.`;
                },

            },

            created() {
                window.addEventListener('theme-changed', (event) => {
                    this.theme = event.detail;
                });
            },

            computed: {
                filteredServices() {
                    // Поиск
                    let filtered = this.search ? this.services.filter(service =>
                            service.title.toLowerCase().includes(this.search.toLowerCase())) : this.services;

                    // Фильтрация
                    return this.category_filter_id != 0
                        ? filtered.filter(service => service.category_id == this.category_filter_id) : filtered;
                }
            },

            mounted() {
                this.getServices();
                this.getQualifications();
                this.getMasters();
                this.getCategories();
                @if(\Illuminate\Support\Facades\Auth::user())
                    this.getUser();
                @endif
            }

        }
        Vue.createApp(App).mount('#Services');
    </script>

    <script>
        const inputTel = document.getElementById('tel');

        inputTel.addEventListener('keydown', (event) => {
            if (event.key === 'Backspace') {
                const digits = inputTel.value.replace(/\D/g, '');
                if (digits.length > 1) {
                    event.preventDefault();
                    const newDigits = digits.slice(0, -1);
                    inputTel.value = formatTel(newDigits);
                } else {
                    inputTel.value = '+7';
                    event.preventDefault();
                }
            }
        });

        inputTel.addEventListener('input', () => {
            const digits = inputTel.value.replace(/\D/g, '');
            inputTel.value = formatTel(digits);
        });

        function formatTel(digits) {
            if (digits.startsWith('8')) digits = '7' + digits.slice(1);
            else if (!digits.startsWith('7')) digits = '7' + digits;

            digits = digits.slice(0, 11);

            let formated_tel = '+7';
            if (digits.length > 1) formated_tel += ' (' + digits.slice(1, 4);
            if (digits.length >= 4) formated_tel += ') ' + digits.slice(4, 7);
            if (digits.length >= 7) formated_tel += ' ' + digits.slice(7, 9);
            if (digits.length >= 9) formated_tel += ' ' + digits.slice(9, 11);

            return formated_tel;
        }
    </script>

@endsection
