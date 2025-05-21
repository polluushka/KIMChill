@extends('layout.app')
@section('title')
    Мой профиль мастера
@endsection
@section('main')
    <div class="container" id="Master">
        <div class="min-container">
            <div class="master" :class="theme === 'light' ? 'p-light':'p-night'">
                <div class="title">
                    <h2>Мой профиль мастера</h2>
                </div>

                <div class="master-info mb-2">
                    <p style="margin-bottom: 1rem" class="category"><span>Специализация:</span> @{{ master.specialization }}</p>
                    <p style="margin-bottom: 1rem" class="category"><span>Квалификация:</span> @{{ qualification.title }}</p>
                    <p style="margin-bottom: 0.5rem" class="category"><span>Описание:</span></p>
                    <p class="description">
                        @{{ master.description }}
                    </p>
                </div>


                <div class="old-services mb-2">
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
                    <div class="list mb-1" v-if="applications_past.length > 0">
                        <table class="table-list">
                            <thead>
                            <tr>
                                <td style="width:50%"><span>Процедура</span></td>
                                <td style="width:20%"><span>Сумма</span></td>
                                <td style="width:30%"><span>Дата/Время</span></td>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-for="(application, index) in sortedApplicationsPast">
                                <tr v-if="index < 5 || applications_past_open_short === true">
                                    <td>@{{ application.service.title }}</td>
                                    <td>@{{ application.discounted_price }}</td>
                                    <td>@{{ date_format(application.date) }}/@{{ application.time }}</td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>

                    <div class="button-end" v-if="sortedApplicationsPast.length > 0">
                        <button class="full-btn" @click="application_past_pagination"
                                v-if="applications_past_open_short === false && applications_past.length > 5">ЕЩЁ...</button>
                        <button class="full-btn" @click="application_past_pagination"
                                v-if="applications_past_open_short === true && applications_past.length > 5">Свернуть</button>
                    </div>

                    <div style="margin-bottom: 1rem" v-if="applications_past.length === 0">
                        <p style="text-align: center">У вас нет проведённых записей</p>
                    </div>
                </div>

                <div class="master-application mb-2">
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
                                            <td><p><span>Телефон:</span></p></td>
                                            <td><p>@{{ application.tel }}</p></td>
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

                <div class="works mb-3">
                    <div class="title">
                        <h2>Мои работы</h2>
                    </div>

                    <div class="works-container" v-if="works.length > 0">
                        <div class="work" v-for="(work, index) in pagination_works">
                            <div class="opacity-work">
                                <button type="button" class="btn-danger-form" @click="delete_work_modal(work.id)">Удалить</button>
                            </div>

                            <img :src="`/${work.img}`" :alt="`work ${index + 1}`">
                        </div>
                    </div>

                    <div style="margin-bottom: 1rem" v-else>
                        <p style="text-align: center">У вас пока не добавлено ни одной работы. Скорее исправьте это!</p>
                    </div>

                    <div class="space-between">
                        <button type="button" class="btn-empty p-btn-empty" @click="store_work_modal">Добавить</button>
                        <button v-if="works.length > 6 && pagination_works.length == 6" class="full-btn" type="button"
                                @click="get_pagination_works">ЕЩЁ...</button>
                        <button v-if="works.length > 6 && pagination_works.length > 6" class="full-btn" type="button"
                                @click="get_pagination_works">Свернуть</button>
                    </div>

                </div>
            </div>
        </div>

        {{--        save-work--}}
        <div class="modal-container" id="createWorkModal">
            <div class="modal-inside">
                <form @submit.prevent="storeWork" id="StoreWorkForm">
                    <div class="title">
                        <h1>Создание категории</h1>
                    </div>

                    <div class="mb-1">
                        <div class="form-settings" :class="errors_works.img ? 'is-invalid':''">
                            <input type="file" name="img[]" id="img" hidden multiple @change="customImgInput">
                            <label for="img" class="custom-file-upload">
                                Загрузить файл
                            </label>
                        </div>

                        <div class="invalid-feedback" v-for="error in errors_works.img">
                            @{{ error }}
                        </div>
                    </div>

                    <div class="button-end">
                        <button class="secondary-btn" type="button" @click="store_work_modal">Отмена</button>
                        <button class="btn-full-form" type="submit">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>

        {{--        delete-work--}}
        <div class="modal-container" id="deleteWorkModal">
            <div class="modal-inside">
                <div>
                    <p>Вы уверены, что хотите удалить это фото? Это действие невозможно отменить.</p>
                    <div class="button-end">
                        <button class="secondary-btn" type="button" @click="delete_work_modal">Отмена</button>
                        <button class="btn-danger-form" type="button" @click="deleteWork">Удалить</button>
                    </div>
                </div>
            </div>
        </div>

    </div>


    @include('layout.footer')

    <script>
        const App = {
            data() {
                return {
                    errors_works: [],
                    master: '',
                    qualification: '',
                    applications_future: [],
                    applications_future_open_short: false,
                    applications_past: [],
                    applications_past_open_short: false,
                    works: [],
                    pagination_works: [],
                    pagination_works_copy: [],
                    delete_id: 0,
                    sorted: 1,

                    theme: localStorage.getItem('theme') || 'light'
                }
            },

            methods: {

                // get-methods
                async getMaster() {
                    const response = await fetch('{{route('getMeMaster')}}');
                    this.master = await response.json();
                    this.qualification = this.master.qualification;
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
                    this.applications_future = this.master.applications_future;
                    this.applications_past = this.master.applications_past;
                },

                //store-methods
                async storeWork() {
                    let form = document.getElementById('StoreWorkForm');
                    let data = new FormData(form);
                    data.append('master_id', this.master.id);
                    const response = await fetch('{{route('saveWork')}}', {
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        },
                        body:data
                    });

                    if(response.status === 201) {
                        form.reset();
                        document.querySelector('.custom-file-upload').textContent = 'Загрузить файл';
                        this.getMaster();
                        this.store_work_modal();
                    }

                    if(response.status === 400) {
                        this.errors_works = await response.json();
                    }

                },

                //delete-methods
                async deleteWork() {
                    const response = await fetch('{{route('deleteWork')}}', {
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}',
                            'Content-Type': 'application/json'
                        },
                        body:JSON.stringify({
                            id:this.delete_id
                        })
                    });

                    if(response.status === 200) {
                        this.delete_work_modal();
                        this.getMaster();
                    }

                },

                store_work_modal() {
                    document.getElementById('createWorkModal').classList.toggle('modal-container-opacity');
                },
                delete_work_modal(id) {
                    document.getElementById('deleteWorkModal').classList.toggle('modal-container-opacity');
                    this.delete_id = id;
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
                get_pagination_works() {
                    if (this.pagination_works.length <= 6) {
                        this.pagination_works = this.works;
                    } else {
                        this.pagination_works = this.pagination_works_copy;
                    }
                },

                customImgInput() {
                    let imgInput = document.getElementById('img');
                    let arrayImgs = imgInput.files;
                    const imgNames = Array.from(arrayImgs).map(file => file.name).join(', ');
                    document.querySelector('.custom-file-upload').textContent = imgNames || 'Загрузить файл';
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
                this.getMaster();
            }

        }

        Vue.createApp(App).mount('#Master');
    </script>

@endsection
