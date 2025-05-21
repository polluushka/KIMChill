@extends('layout.app')
@section('title')
    Регистрация
@endsection
@section('main')
    <div class="container" id="Reg">
        <div class="min-container">
            <form class="form" id="FormReg" @submit.prevent="reg_user" :class="theme === 'light' ? 'p-light':'p-night'">

                <div id="first-screen" class="form-container">
                    <div class="title">
                        <h1>Регистрация</h1>
                    </div>

                    <div class="mb-1">
                        <div class="mb-03">
                            <label for="name" class="label-form-settings">Имя*</label>
                        </div>
                        <input type="text" name="name" id="name" class="form-settings"
                               :class="{
                                    'p-light': theme === 'light',
                                    'p-night': theme !== 'light',
                                    'is-invalid': errors.name
                               }">

                        <div class="invalid-feedback" v-for="error in errors.name">
                            @{{ error }}
                        </div>
                    </div>

                    <div class="mb-1">
                        <div class="mb-03">
                            <label for="surname" class="label-form-settings">Фамилия</label>
                        </div>
                        <input type="text" name="surname" id="surname" class="form-settings"
                               :class="{
                                    'p-light': theme === 'light',
                                    'p-night': theme !== 'light',
                                    'is-invalid': errors.surname
                               }">

                        <div class="invalid-feedback" v-for="error in errors.surname">
                            @{{ error }}
                        </div>
                    </div>

                    <div class="mb-1">
                        <div class="mb-03">
                            <label for="birthday" class="label-form-settings">День рождения*</label>
                        </div>
                        <input type="date" name="birthday" id="birthday" class="form-settings date-form"
                               :class="{
                                    'p-light': theme === 'light',
                                    'p-night': theme !== 'light',
                                    'date-form-light': theme === 'light',
                                    'date-form-night': theme !== 'light',
                                    'is-invalid': errors.birthday,
                               }">

                        <div class="invalid-feedback" v-for="error in errors.birthday">
                            @{{ error }}
                        </div>
                    </div>

                    <div class="space-between">
                        <p><a href="{{route('authorization')}}" class="link-a">Авторизироваться</a></p>
                        <button type="button" class="btn-empty-form" @click="switch_screens">Далее</button>
                    </div>
                </div>

                <div id="second-screen" class="form-container opacity-none">
                    <div class="title">
                        <h1>Регистрация</h1>
                    </div>

                    <div class="mb-1">
                        <div class="mb-03">
                            <label for="tel" class="label-form-settings">Номер телефона*</label>
                        </div>
                        <input type="tel" name="tel" id="tel" class="form-settings"
                               :class="{
                                    'p-light': theme === 'light',
                                    'p-night': theme !== 'light',
                                    'is-invalid': errors.tel
                               }">

                        <div class="invalid-feedback" v-for="error in errors.tel">
                            @{{ error }}
                        </div>
                    </div>

                    <div class="mb-1">
                        <div class="mb-03">
                            <label for="password" class="label-form-settings">Пароль*</label>
                        </div>
                        <input type="password" name="password" id="password" class="form-settings"
                               :class="{
                                    'p-light': theme === 'light',
                                    'p-night': theme !== 'light',
                                    'is-invalid': errors.password
                               }">

                        <div class="invalid-feedback" v-for="error in errors.password">
                            @{{ error }}
                        </div>
                    </div>

                    <div class="mb-1">
                        <div class="mb-03">
                            <label for="password_confirmation" class="label-form-settings">Повторите пароль*</label>
                        </div>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-settings"
                               :class="{
                                    'p-light': theme === 'light',
                                    'p-night': theme !== 'light',
                                    'is-invalid': errors.password_confirmation
                               }">

                        <div class="invalid-feedback" v-for="error in errors.password_confirmation">
                            @{{ error }}
                        </div>
                    </div>

                    <div class="container-checkbox">
                        <input type="checkbox" name="rules" id="rules" class="form-settings-checkbox" :class="errors.rules ? 'is-invalid':''">
                        <label for="rules" class="label-form-settings label-form-settings-checkbox">Я согласен на обработку персональных данных</label>
                    </div>

                    <div class="space-between">
                        <button type="button" class="btn-empty-form" @click="switch_screens">Назад</button>
                        <button class="btn-full-form">Регистрация</button>
                    </div>
                </div>

            </form>

        </div>
    </div>

    <script>
        const App = {
            data() {
                return {
                    errors: [],
                    theme: localStorage.getItem('theme') || 'light'
                }
            },

            methods: {
                switch_screens() {
                    document.getElementById('first-screen').classList.toggle('opacity-none');
                    document.getElementById('second-screen').classList.toggle('opacity-none');
                },

                async reg_user() {
                    let data = new FormData(document.getElementById('FormReg'));
                    const response = await fetch('{{route('reg')}}', {
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
                    }

                },

            },

            created() {
                window.addEventListener('theme-changed', (event) => {
                    this.theme = event.detail;
                });

            },
        }

        Vue.createApp(App).mount('#Reg');
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
