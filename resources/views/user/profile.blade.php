@extends('layout.app')
@section('title')
    Мой профиль
@endsection
@section('main')
    <div class="container" id="Profile">
        <div class="min-container">
            <div :class="theme === 'light' ? 'p-light':'p-night'" class="profile-header mb-2">
                <div class="img-container">
                    <img :src="`${user.img}`" alt="user_photo" v-if="user.img != ''">
                    <svg v-else viewBox="0 0 304 304" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M214.249 120.277C214.249 152.83 186.16 179.717 150.88 179.717C115.599 179.717 87.5107 152.83 87.5107 120.277C87.5107 87.7231 115.599 60.8359 150.88 60.8359C186.16 60.8359 214.249 87.7231 214.249 120.277Z" :stroke="theme === 'light' ? 'black':'white'" stroke-width="10"/>
                        <path d="M264.83 294.265C264.83 282.274 262.388 270.399 257.643 259.321C252.898 248.242 245.944 238.176 237.177 229.696C228.41 221.217 218.002 214.491 206.547 209.902C195.093 205.313 182.815 202.951 170.417 202.951" :stroke="theme === 'light' ? 'black':'white'" stroke-width="10"/>
                        <path d="M39.292 294.265C39.292 282.274 41.8066 270.399 46.6921 259.321C51.5777 248.242 58.7386 238.176 67.766 229.696C76.7933 221.217 87.5104 214.491 99.3052 209.902C111.1 205.313 123.742 202.951 136.508 202.951H170.421" :stroke="theme === 'light' ? 'black':'white'" stroke-width="10"/>
                        <rect x="5" y="5" width="294" height="294" rx="15" :stroke="theme === 'light' ? 'black':'white'" stroke-width="10"/>
                    </svg>
                </div>

                <div class="profile-info">
                    <div class="title">
                        <h1>@{{ user.name }} @{{ user.surname }}</h1>
                    </div>
                    <table class="text">
                        <tr>
                            <td><p><span>Дата рождения:</span></p></td>
                            <td><p>@{{ date_format(user.birthday) }}</p></td>
                        </tr>
                        <tr>
                            <td><p><span>Номер телефона:</span></p></td>
                            <td><p>@{{ user.tel }}</p></td>
                        </tr>
                        <tr>
                            <td><p><span>Скидка:</span></p></td>
                            <td><p>@{{ user.discount }}%</p></td>
                        </tr>
                    </table>
                    <div class="button-end">
                        <button class="btn-empty p-btn-empty" type="button" @click="edit_user_modal">Редактировать</button>
                    </div>

                </div>
            </div>

            <div :class="theme === 'light' ? 'p-light':'p-night'" class="old-services mb-2">
                <div class="title">
                    <h2>Проведённые процедуры</h2>
                </div>

                <div class="mb-1 button-end">
                    <select style="width: 30%" v-model="sorted" name="sorted" id="sorted" class="form-settings select-form"
                            :class="{
                                    'p-light': theme === 'light',
                                    'p-night': theme !== 'light',
                                    'options-light': theme === 'light',
                                    'options-night': theme !== 'light',
                                    'select-form-light': theme === 'light',
                                    'select-form-night': theme !== 'light'
                                }">
                        <option value="1" selected>Новые</option>
                        <option value="2" selected>Старые</option>
                    </select>
                </div>

                <div class="list mb-1">
                    <table class="table-list" v-if="applications_past.length > 0">
                        <thead>
                        <tr>
                            <td style="width:45%"><span>Процедура</span></td>
                            <td style="width:20%"><span>Мастер</span></td>
                            <td style="width:15%"><span>Сумма</span></td>
                            <td style="width:20%"><span>Дата/Время</span></td>
                        </tr>
                        </thead>
                        <tbody>
                        <template v-for="(application, index) in sortedApplicationsPast">
                            <tr v-if="index < 5 || applications_past_open_short === true">
                                <td>@{{ application.service.title }}</td>
                                <td>@{{ application.master.name }}</td>
                                <td>@{{ application.discounted_price }}</td>
                                <td>@{{ date_format(application.date) }}/@{{ application.time }}</td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                </div>

                <div class="button-end" v-if="applications_past.length > 0">
                    <button class="full-btn" @click="application_past_pagination"
                            v-if="applications_past_open_short === false && applications_past.length > 5">ЕЩЁ...</button>
                    <button class="full-btn" @click="application_past_pagination"
                            v-if="applications_past_open_short === true && applications_past.length > 5">Свернуть</button>
                </div>

                <div style="margin-bottom: 1rem" v-else>
                    <p style="text-align: center">У вас нет проведённых записей</p>
                </div>
            </div>

            <div :class="theme === 'light' ? 'p-light':'p-night'" class="master-application mb-3">
                <div class="title">
                    <h2>Запланировано</h2>
                </div>

                <div class="applications-container m-1" v-if="applications_future.length > 0">
                    <div v-for="(application, index) in applications_future">
                        <div class="application-card" v-if="index < 3 || applications_future_open_short === true">
                            <div class="title">
                                <h4>@{{ application.service.title }}</h4>
                            </div>
                            <div class="space-between table-application">
                                <table class="right-application" style="width: 50%">
                                    <tr class="space-between" style="width: 100%; margin-bottom: 0.5rem">
                                        <td><p><span>Дата:</span></p></td>
                                        <td><p>@{{ date_format(application.date) }}</p></td>
                                    </tr>

                                    <tr style="margin-bottom: 0.5rem" class="space-between">
                                        <td><p><span>Время:</span></p></td>
                                        <td><p>@{{ application.time }}</p></td>
                                    </tr>

                                    <tr style="margin-bottom: 0.5rem" class="space-between">
                                        <td><p><span>Длит.:</span></p></td>
                                        <td><p>@{{ duration_format(application.duration) }}</p></td>
                                    </tr>

                                    <tr class="space-between">
                                        <td><p><span>Мастер:</span></p></td>
                                        <td><p>@{{ application.master.name }}</p></td>
                                    </tr>
                                </table>
                                <table class="left-application" style="width: 50%">

                                    <tr style="margin-bottom: 0.5rem" class="space-between" style="width: 100%">
                                        <td><p><span>Стоимость:</span></p></td>
                                        <td><p>@{{ application.price }} руб.</p></td>
                                    </tr>

                                    <tr style="margin-bottom: 2rem" class="space-between">
                                        <td><span>Скидка:</span></td>
                                        <td v-if="application.discount"><p>@{{ application.discount }}%</p></td>
                                        <td v-else><p>0%</p></td>
                                    </tr>


                                    <tr class="space-between">
                                        <td><p><span>ИТОГО:</span></p></td>
                                        <td><p>@{{ application.discounted_price }} руб.</p></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="button-end" v-if="applications_future.length > 0">
                    <button class="full-btn" @click="application_future_pagination"
                            v-if="applications_future_open_short === false && applications_future.length > 3">ЕЩЁ...</button>
                    <button class="full-btn" @click="application_future_pagination"
                            v-if="applications_future_open_short === true && applications_future.length > 3">Свернуть</button>
                </div>

                <div style="margin-bottom: 1rem" v-else>
                    <p style="text-align: center">У вас нет запланированных записей</p>
                </div>
            </div>

        </div>

        {{--        edit-user--}}
        <div class="modal-container" id="editUserModal">
            <div class="modal-inside">
                <form @submit.prevent="editUser" id="EditUserForm">
                    <div class="title">
                        <h1>Редактирование профиля</h1>
                    </div>

                    <div :class="edit_message ? 'alert-success':''">
                        @{{ edit_message }}
                    </div>

                    <div class="mb-1 space-between-start">
                        <div style="width: 49%">
                            <div class="mb-03">
                                <label for="name" class="label-form-settings mb-03">Имя</label>
                            </div>
                            <input v-model="user_obj.name" type="text" name="name" id="name" class="form-settings"
                                   :class="errors.name ? 'is-invalid':''">

                            <div class="invalid-feedback" v-for="error in errors.name">
                                @{{ error }}
                            </div>
                        </div>
                        <div style="width: 49%">
                            <div class="mb-03">
                                <label for="surname" class="label-form-settings">Фамилия</label>
                            </div>
                            <input v-model="user_obj.surname" type="text" name="surname" id="surname"
                                   class="form-settings" :class="errors.surname ? 'is-invalid':''">

                            <div class="invalid-feedback" v-for="error in errors.surname">
                                @{{ error }}
                            </div>
                        </div>
                    </div>

                    <div class="mb-1">
                        <div class="mb-03">
                            <label for="tel" class="label-form-settings">Номер телефона</label>
                        </div>
                        <input type="tel" v-model="user_obj.tel" name="tel" id="tel" class="form-settings"
                               :class="errors.tel ? 'is-invalid':''">

                        <div class="invalid-feedback" v-for="error in errors.tel">
                            @{{ error }}
                        </div>
                    </div>

                    <div class="mb-1">
                        <div class="mb-03">
                            <label for="img" class="label-form-settings">Изображение профиля</label>
                        </div>
                        <div class="form-settings" :class="errors.img ? 'is-invalid':''">
                            <input type="file" name="img" id="img" hidden @change="customImgInput">
                            <label for="img" class="custom-file-upload">
                                Загрузить файл
                            </label>
                        </div>
                        <div class="invalid-feedback" v-for="error in errors.img">
                            @{{ error }}
                        </div>
                    </div>


                    <div class="space-between">
                        <p style="font-size: 0.9rem; margin: 0">
                            <button type="button" style="font-size: 0.9rem" class="link-a"
                                              @click="password_edit_modal">поменять пароль</button> или
                            <button @click="delete_account_modal" type="button" style="font-size: 0.9rem"
                                    class="link-a">удалить аккаунт</button>
                        </p>
                        <div class="button-end">
                            <button class="secondary-btn" type="button" @click="edit_user_modal">Отмена</button>
                            <button class="btn-full-form" type="submit">Сохранить</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>

        {{--        edit-password--}}
        <div class="modal-container" id="editPasswordModal">
            <div class="modal-inside">
                <form @submit.prevent="editPasswordUser" id="EditPasswordForm">
                    <div class="title">
                        <h1>Изменение пароля</h1>
                    </div>

                    <div :class="edit_password_message ? 'alert-success':''">
                        @{{ edit_password_message }}
                    </div>

                    <div class="mb-1">
                        <div class="mb-03">
                            <label for="password" class="label-form-settings">Пароль</label>
                        </div>
                        <input type="password" name="password" id="password" class="form-settings"
                               :class="errors.password ? 'is-invalid':''">

                        <div class="invalid-feedback" v-for="error in errors.password">
                            @{{ error }}
                        </div>
                    </div>

                    <div class="mb-1">
                        <div class="mb-03">
                            <label for="password_confirmation" class="label-form-settings">Повторите пароль</label>
                        </div>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="form-settings" :class="errors.password_confirmation ? 'is-invalid':''">

                        <div class="invalid-feedback" v-for="error in errors.password_confirmation">
                            @{{ error }}
                        </div>
                    </div>

                    <div class="button-end">
                        <button class="secondary-btn" type="button" @click="password_edit_modal">Отмена</button>
                        <button class="btn-full-form" type="submit">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>

        {{--        delete-account--}}
        <div class="modal-container" id="DeleteAccountModal">
            <div class="modal-inside">
                <form @submit.prevent="deleteUser" id="DeleteAccountForm">
                    <div class="title">
                        <h1>Удаление аккаунта</h1>
                    </div>

                    <p>Вы уверены, что хотите удалить аккаунт? Все ваши данные будут утеряны. Это действие нельзя отменить.</p>

                    <div :class="delete_account_message ? 'alert-error':''">
                        @{{ delete_account_message }}
                    </div>

                    <div class="mb-1">
                        <div class="mb-03">
                            <label for="password_delete" class="label-form-settings">Введите пароль для подтверждения</label>
                        </div>
                        <input type="password" name="password_delete" id="password_delete" class="form-settings"
                               :class="errors.password_delete ? 'is-invalid':''">

                        <div class="invalid-feedback" v-for="error in errors.password_delete">
                            @{{ error }}
                        </div>
                    </div>

                    <div class="button-end">
                        <button class="secondary-btn" type="button" @click="delete_account_modal">Отмена</button>
                        <button class="btn-danger-form" type="submit">Удалить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('layout.footer')

    <script>
        const App = {
            data() {
                return {
                    edit_message: '',
                    edit_password_message: '',
                    delete_account_message: '',
                    errors: [],
                    user: '',
                    applications_future: [],
                    applications_future_open_short: false,
                    applications_past: [],
                    applications_past_open_short: false,
                    user_obj: {
                        name: '',
                        surname: '',
                        tel: '',
                    },

                    sorted: 1,
                    theme: localStorage.getItem('theme') || 'light'
                }
            },

            methods: {

                // get-methods
                async getUser() {
                    const response = await fetch('{{route('getUser')}}');
                    this.user = await response.json();
                    this.applications_future = this.user.applications_future;
                    this.applications_past = this.user.applications_past;
                    this.user_obj.name = this.user.name;
                    this.user_obj.surname = this.user.surname;
                    this.user_obj.tel = this.user.tel;
                },

                //edit-methods
                async editUser() {
                    let form = document.getElementById('EditUserForm');
                    let data = new FormData(form);
                    const response = await fetch('{{route('editUser')}}', {
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        },
                        body:data
                    });

                    if(response.status === 200) {
                        this.edit_message = await response.json();
                        this.errors = [];
                        document.getElementById('img').value = null;
                        document.querySelector('.custom-file-upload').textContent = 'Загрузить файл';
                        this.getUser();
                    }

                    if(response.status === 400) {
                        this.errors = await response.json();
                        this.edit_message = '';
                    }

                },
                async editPasswordUser() {
                    let form = document.getElementById('EditPasswordForm');
                    let data = new FormData(form);
                    const response = await fetch('{{route('editPasswordUser')}}', {
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        },
                        body:data
                    });

                    if(response.status === 200) {
                        this.edit_password_message = await response.json();
                        this.errors = [];
                        form.reset();
                        this.getUser();
                    }

                    if(response.status === 400) {
                        this.errors = await response.json();
                        this.edit_password_message = '';
                    }

                },

                //delete-methods
                async deleteUser() {
                    let form = document.getElementById('DeleteAccountForm');
                    let data = new FormData(form);
                    const response = await fetch('{{route('deleteUser')}}', {
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
                        this.errors = await response.json();
                        this.delete_account_message = '';
                    }

                    if(response.status === 403) {
                        this.errors = [];
                        this.delete_account_message = await response.json();
                    }

                },

                edit_user_modal() {
                    document.getElementById('editUserModal').classList.toggle('modal-container-opacity');
                    this.errors = [];
                    this.edit_message = '';
                },
                password_edit_modal() {
                    this.edit_user_modal();
                    document.getElementById('editPasswordModal').classList.toggle('modal-container-opacity');
                    this.errors = [];
                    this.edit_password_message = '';
                },
                delete_account_modal() {
                    this.edit_user_modal();
                    this.errors = [];
                    this.edit_password_message = '';
                    document.getElementById('DeleteAccountModal').classList.toggle('modal-container-opacity');
                },

                date_format(date) {
                    let day = new Date(date).getDate();
                    let month = new Date(date).getMonth() + 1;
                    let year = new Date(date).getFullYear();
                    if (day < 10) {
                        day = '0' + day;
                    }

                    if (month < 10) {
                        month = '0' + month;
                    }
                    return `${day}.${month}.${year}`;
                },

                duration_format(duration) {
                    let hours = Math.floor(duration / 60);
                    let minutes = duration - (Math.floor(duration / 60) * 60);
                    return `${hours} ч. ${minutes} мин.`;
                },

                //pagination
                application_future_pagination() {
                    if (this.applications_future_open_short === false) this.applications_future_open_short = true;
                    else this.applications_future_open_short = false;
                },
                application_past_pagination() {
                    if (this.applications_past_open_short === false) this.applications_past_open_short = true;
                    else this.applications_past_open_short = false;
                },

                customImgInput() {
                    let imgInput = document.getElementById('img');
                    let nameImg = imgInput.value.split('\\').pop();
                    document.querySelector('.custom-file-upload').textContent = nameImg || 'Загрузить файл';
                }

            },

            computed: {
                sortedApplicationsPast() {
                    return this.sorted == 1
                        ? this.applications_past : [...this.applications_past].sort((application1, application2) =>
                            new Date(application1.date).setHours(application1.time.split(':')[0], application1.time.split(':')[1]) -
                            new Date(application2.date).setHours(application2.time.split(':')[0], application2.time.split(':')[1]));
                },
            },

            created() {
                window.addEventListener('theme-changed', (event) => {
                    this.theme = event.detail;
                });
            },

            mounted() {
                this.getUser();
            }

        }

        Vue.createApp(App).mount('#Profile');
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
