@extends('layout.app')
@section('title')
    Авторизация
@endsection
@section('main')
    <div class="container" id="Auth">
        <div class="min-container">
            <form class="form" id="FormAuth" @submit.prevent="auth_user">

                <div class="form-container" :class="theme === 'light' ? 'p-light':'p-night'">
                    <div class="title">
                        <h1>Авторизация</h1>
                    </div>

                    <div :class="message ? 'alert-error':''">
                        @{{ message }}
                    </div>

                    <div class="mb-1">
                        <div class="mb-03">
                            <label for="tel" class="label-form-settings">Номер телефона</label>
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
                            <label for="password" class="label-form-settings">Пароль</label>
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


                    <div class="space-between">
                        <p><a href="{{route('registration')}}" class="link-a">Зарегистрироваться</a></p>
                        <button class="btn-full-form">Войти</button>
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
                    message: '',
                    theme: localStorage.getItem('theme') || 'light'
                }
            },

            methods: {
                async auth_user() {
                    let data = new FormData(document.getElementById('FormAuth'));
                    const response = await fetch('{{route('auth')}}', {
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
                        this.message = '';
                    }

                    if(response.status === 403) {
                        this.message = await response.json();
                        this.errors = '';
                    }
                }
            },

            created() {
                window.addEventListener('theme-changed', (event) => {
                    this.theme = event.detail;
                });
            },
        }

        Vue.createApp(App).mount('#Auth');
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
