@extends('layout.app')
@section('title')
    {{$master->specialization}} {{$master->name}}
@endsection
@section('main')
    <div class="container" id="Master">
        <div class="min-container">
            <div class="master-header mb-2" :class="theme === 'light' ? 'p-light':'p-night'">
                <div class="img-container">
                    <img :src="`/${master.img}`" alt="" v-if="master.img != ''">
                    <svg v-else viewBox="0 0 304 304" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M214.249 120.277C214.249 152.83 186.16 179.717 150.88 179.717C115.599 179.717 87.5107 152.83 87.5107 120.277C87.5107 87.7231 115.599 60.8359 150.88 60.8359C186.16 60.8359 214.249 87.7231 214.249 120.277Z" :stroke="theme === 'light' ? 'black':'white'" stroke-width="10"/>
                        <path d="M264.83 294.265C264.83 282.274 262.388 270.399 257.643 259.321C252.898 248.242 245.944 238.176 237.177 229.696C228.41 221.217 218.002 214.491 206.547 209.902C195.093 205.313 182.815 202.951 170.417 202.951" :stroke="theme === 'light' ? 'black':'white'" stroke-width="10"/>
                        <path d="M39.292 294.265C39.292 282.274 41.8066 270.399 46.6921 259.321C51.5777 248.242 58.7386 238.176 67.766 229.696C76.7933 221.217 87.5104 214.491 99.3052 209.902C111.1 205.313 123.742 202.951 136.508 202.951H170.421" :stroke="theme === 'light' ? 'black':'white'" stroke-width="10"/>
                        <rect x="5" y="5" width="294" height="294" rx="15" :stroke="theme === 'light' ? 'black':'white'" stroke-width="10"/>
                    </svg>
                </div>
                <div class="master-info">
                    <div class="title">
                        <h1>@{{ master.specialization }} <span style="color: #BC13FE">@{{ master.name }}</span></h1>
                    </div>
                    <div class="text-master">
                        <p class="category"><span>Квалификация:</span> @{{ qualification.title }}</p>
                        <p class="category"><span>Описание:</span></p>
                        <div class="description">
                            <p v-if="open_short === false">@{{ description_short }}
                                <button class="pagination-btn"
                                        v-if="description.trim().split(/\s+/).length > 25"
                                        @click="toggle_pagination">ещё...</button></p>
                            <p v-else>@{{ description }}
                                <button class="pagination-btn"
                                        v-if="description.trim().split(/\s+/).length > 25"
                                        @click="toggle_pagination">свернуть...</button>
                            </p>

                        </div>
                    </div>
                </div>
            </div>
            <div class="works mb-2"  :class="theme === 'light' ? 'p-light':'p-night'">
                <div class="title">
                    <h2>Работы мастера</h2>
                </div>

                <div class="works-container" v-if="works.length > 0">
                    <div class="work" v-for="(work, index) in pagination_works">
                        <img :src="`/${work}`" :alt="`work ${index + 1}`">
                    </div>
                </div>

                <div style="margin-bottom: 1rem" v-else>
                    <p style="text-align: center">У мастера пока не добавлено ни одной работы</p>
                </div>

                <div class="button-end">
                    <button v-if="works.length > 6 && pagination_works.length == 6" class="full-btn" type="button"
                            @click="get_pagination_works">ЕЩЁ...</button>
                    <button v-if="works.length > 6 && pagination_works.length > 6" class="full-btn" type="button"
                            @click="get_pagination_works">Свернуть</button>
                </div>
            </div>
            <div class="master-application mb-3" :class="theme === 'light' ? 'p-light':'p-night'">
                <div class="title">
                    <h2>Запись к мастеру</h2>
                </div>

                @auth()
                <form id="StoreApplicationForm" @submit.prevent="storeApplication">

                    <div :class="message_create_applications_error ? 'alert-error':''">
                        @{{ message_create_applications_error }}
                    </div>

                    <div class="mb-03">
                        <select v-model="month_id" name="month" id="month"
                                :class="{
                                    'p-light': theme === 'light',
                                    'p-night': theme !== 'light',
                                    'options-light': theme === 'light',
                                    'options-night': theme !== 'light',
                                    'select-form-light': theme === 'light',
                                    'select-form-night': theme !== 'light'
                                }"
                                class="form-settings select-form select-button" @change="getInfoMonth">
                            <option value="0" selected>Месяц</option>
                            <option v-for="month in months" :value="month.id">@{{ month.name_month }}, @{{ month.year }}</option>
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
                                            :class="[
                                                theme === 'light' ? 'p-light' : 'p-night',
                                                focus_btn_id == day ? 'btn-calendar-focus' : '',
                                                {
                                                'btn-calendar-hidden-light': btnHidden(day) && theme === 'light' && !btnColor(day),
                                                'btn-calendar-hidden-night': btnHidden(day) && theme !== 'light' && !btnColor(day),
                                                'btn-calendar-date-yes': btnColor(day)
                                                }
                                            ]">
                                        @{{ day }}</button>
                                </td>
                            </tr>
                            <tr v-for="(week, index_week) in (Math.floor(selected_mouth.days / 7))">
                                <td v-for="day in 7" :key="(7 - selected_mouth.skipDays) + day" v-if="index_week === 0">
                                    <button name="day[]" :id="`${(7 - selected_mouth.skipDays) + day}`" type="button" class="btn-calendar"
                                            @click="getFocusBtn((7 - selected_mouth.skipDays) + day)"
                                            :class="[
                                                theme === 'light' ? 'p-light' : 'p-night',
                                                focus_btn_id == (7 - selected_mouth.skipDays) + day ? 'btn-calendar-focus' : '',
                                                {
                                                'btn-calendar-hidden-light': btnHidden((7 - selected_mouth.skipDays) + day) && theme === 'light' && !btnColor(day),
                                                'btn-calendar-hidden-night': btnHidden((7 - selected_mouth.skipDays) + day) && theme !== 'light' && !btnColor(day),
                                                'btn-calendar-date-yes': btnColor((7 - selected_mouth.skipDays) + day)
                                                }
                                            ]">
                                        @{{ (7 - selected_mouth.skipDays) + day }}</button>
                                </td>
                                <td v-for="day in 7" :key="((7 - selected_mouth.skipDays) + (index_week * 7)) + day" v-if="(index_week !== 0)">
                                    <button name="day[]" :id="`${(7 - selected_mouth.skipDays) + (index_week * 7) + day}`"
                                            type="button" class="btn-calendar"
                                            @click="getFocusBtn((7 - selected_mouth.skipDays) + (index_week * 7) + day)"
                                            v-if="(((7 - selected_mouth.skipDays) + (index_week * 7) + day) <= selected_mouth.days)"
                                            :class="[
                                                theme === 'light' ? 'p-light' : 'p-night',
                                                focus_btn_id == (7 - selected_mouth.skipDays) + (index_week * 7) + day ? 'btn-calendar-focus' : '',
                                                {
                                                'btn-calendar-hidden-light': btnHidden((7 - selected_mouth.skipDays) + (index_week * 7) + day) && theme === 'light' && !btnColor(day),
                                                'btn-calendar-hidden-night': btnHidden((7 - selected_mouth.skipDays) + (index_week * 7) + day) && theme !== 'light' && !btnColor(day),
                                                'btn-calendar-date-yes': btnColor((7 - selected_mouth.skipDays) + (index_week * 7) + day)
                                                }
                                            ]">
                                        @{{ (7 - selected_mouth.skipDays) + (index_week * 7) + day }}</button>
                                </td>
                            </tr>

                            <tr class="" v-if="(Math.floor(selected_mouth.days / 7) * 7) + (7 - selected_mouth.skipDays) < selected_mouth.days">
                                <td v-for="day in 7" :key="(7 - selected_mouth.skipDays) + (Math.floor(selected_mouth.days / 7) * 7) + day">
                                    <button name="day[]" :id="`${(7 - selected_mouth.skipDays) + (Math.floor(selected_mouth.days / 7) * 7) + day}`"
                                            type="button" class="btn-calendar"
                                            @click="getFocusBtn((7 - selected_mouth.skipDays) + (Math.floor(selected_mouth.days / 7) * 7) + day)"
                                            v-if="(7 - selected_mouth.skipDays) + (Math.floor(selected_mouth.days / 7) * 7) + day <= selected_mouth.days"
                                            :class="[
                                                theme === 'light' ? 'p-light' : 'p-night',
                                                focus_btn_id == (7 - selected_mouth.skipDays) + (Math.floor(selected_mouth.days / 7) * 7) + day ? 'btn-calendar-focus' : '',
                                                {
                                                'btn-calendar-hidden-light': btnHidden((7 - selected_mouth.skipDays) + (Math.floor(selected_mouth.days / 7) * 7) + day) && theme === 'light' && !btnColor(day),
                                                'btn-calendar-hidden-night': btnHidden((7 - selected_mouth.skipDays) + (Math.floor(selected_mouth.days / 7) * 7) + day) && theme !== 'light' && !btnColor(day),
                                                'btn-calendar-date-yes': btnColor((7 - selected_mouth.skipDays) + (Math.floor(selected_mouth.days / 7) * 7) + day)
                                                }
                                            ]">
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

                    <div class="mb-1 space-between-start">
                        <div style="width: 49%">
                            <select v-model="service_id" name="service_id" id="service_id"
                                    :class="{
                                        'p-light': theme === 'light',
                                        'p-night': theme !== 'light',
                                        'options-light': theme === 'light',
                                        'options-night': theme !== 'light',
                                        'select-form-light': theme === 'light',
                                        'select-form-night': theme !== 'light'
                                    }"
                                    class="form-settings select-form select-button" @change="getPriceDuration">
                                <option value="0" selected>Услуга</option>
                                <option v-for="service in master.services" :value="service.id">
                                    @{{ service.title }}
                                </option>
                            </select>
                        </div>
                        <div style="width: 49%">
                            <input type="tel" name="tel" id="tel" class="form-settings" placeholder="Номер телефона"
                                   :class="{
                                        'p-light': theme === 'light',
                                        'p-night': theme !== 'light',
                                        'is-invalid': errors.tel,
                                    }">
                            <div class="invalid-feedback" v-for="error in errors.tel">
                                @{{ error }}
                            </div>
                        </div>
                    </div>


                    @if(\Illuminate\Support\Facades\Auth::user())
                        <div class="mb-1 price-block" v-if="service_id != 0">
                            <p style="margin: 0;"><span>Цена без скидки:</span> @{{ price_duration.price }} ₽</p>
                            <p style="margin: 0;"><span>Скидка:</span> @{{ user.discount }}%</p>
                            <p style="margin: 0;"><span>Итого:</span> @{{ price_duration.price - (price_duration.price * user.discount / 100) }} ₽</p>
                            <p style="margin: 0;"><span>Длительность:</span> @{{ duration_format(price_duration.duration) }}</p>
                        </div>

                        <div class="mb-1 price-block" v-if="service_id == 0">
                            <p style="margin: 0;"><span>Цена без скидки:</span> 0 ₽</p>
                            <p style="margin: 0;"><span>Скидка:</span> @{{ user.discount }}%</p>
                            <p style="margin: 0;"><span>Итого:</span> 0 ₽</p>
                            <p style="margin: 0;"><span>Длительность:</span> 0 минут</p>
                        </div>
                    @endif

                    <div class="button-end">
                        <button class="btn-full-form">Записаться</button>
                    </div>
                </form>
                @endauth

                @guest()
                    <p style="text-align: center" class="mb-2">Чтобы записаться к мастеру,<a href="{{route('authorization')}}">авторизуйтесь!</a></p>
                @endguest
            </div>
        </div>
    </div>

    @include('layout.footer')

    <script>
        const App = {
            data() {
                return {
                    message_create_applications_error: '',
                    message_create_applications: '',
                    errors: [],
                    master: '',
                    user: '',
                    months: [],
                    works: [],
                    pagination_works: [],
                    pagination_works_copy: [],
                    qualification: '',
                    description: '',
                    open_short: false,
                    description_short: '',
                    price_duration: {
                        price: 0,
                        duration: 0,
                    },
                    service_id: 0,

                    selected_mouth: '',
                    calendar: '',
                    month_id: 0,
                    day: '',
                    focus_btn_id: 0,
                    date_times: [],
                    time: '',

                    theme: localStorage.getItem('theme') || 'light'
                }
            },

            methods: {

                // get-methods
                async getMaster() {
                    let master_id = window.location.href.split('/').at(-1);
                    const response = await fetch('{{route('getMaster')}}', {
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}',
                            'Content-Type': 'application/json'
                        },
                        body:JSON.stringify({
                            id: master_id
                        }),
                    });

                    if(response.status === 200) {
                        this.master = await response.json();
                        this.qualification = this.master.qualification;
                        this.description = this.master.description;
                        this.description_short = this.get_pagination_text(this.description);

                        this.works = this.master.works;
                        this.pagination_works = [];
                        for(let i = 0; i < this.works.length; i++) {
                            if (i !== 6) {
                                this.pagination_works.push(this.works[i]);
                            } else {
                                break;
                            }
                        }
                        this.pagination_works_copy = this.pagination_works;

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
                async getUser() {
                    const response = await fetch('{{route('getUser')}}');
                    this.user = await response.json();
                },
                getMonths() {
                    let i = 0;
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
                    data.append('master', this.master.id);
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

                    if(response.status === 400) {
                        this.message_create_applications_error = '';
                        this.errors = await response.json();
                    }

                    if(response.status === 422) {
                        this.message_create_applications_error = await response.json();
                        this.errors = [];
                        this.message_create_applications = '';
                    }

                },


                getPriceDuration() {
                    if (this.service_id != 0) {
                        this.price_duration.price = this.master.qualification.prices.find(price =>
                            price.service_id === this.service_id).price;
                        this.price_duration.duration = this.master.qualification.prices.find(price =>
                            price.service_id === this.service_id).duration;
                    }
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
                    for (let i = 0; i < this.calendar.dates.length; i++) {
                        let date_calendar = this.calendar.dates[i];
                        if (today.getTime() > date.getTime() || date_calendar.hasOwnProperty(day) === false) return true
                    }

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
                        let today = new Date();
                        for (let i = 0; i < this.calendar.dates.length; i++) {
                            let date = this.calendar.dates[i];
                            let date_calendar = new Date(this.selected_mouth.year + '-' + this.selected_mouth.number_month + '-' + day);
                            if (date.hasOwnProperty(day) && today.getTime() < date_calendar.getTime()) return true;
                        }
                    }
                    return false;
                },
                getDateTimes(day) {
                    this.time = 0;
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


                get_pagination_works() {
                    if (this.pagination_works.length <= 6) {
                        this.pagination_works = this.works;
                    } else {
                        this.pagination_works = this.pagination_works_copy;
                    }
                },
                get_pagination_text(text) {
                    if (!text || typeof text !== 'string') return '';
                    let words = text.trim().split(/\s+/).filter(word => word.length > 0);
                    if (words.length <= 25) return text;
                    return words.slice(0, 25).join(' ');
                },
                toggle_pagination() {
                    if (this.open_short === false) this.open_short = true;
                    else this.open_short = false;
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

            mounted() {
                this.getMaster();
                @if(\Illuminate\Support\Facades\Auth::user())
                    this.getUser();
                @endif

            }

        }

        Vue.createApp(App).mount('#Master');
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
