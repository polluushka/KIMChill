@extends('layout.app')
@section('title')
    Главная
@endsection
@section('main')

    <div class="container" id="Welcome">
        <div class="min-container">

            <div class="mb-3 sliders-container">
                <div class="sliders">
                    <video v-for="(slider, index) in sliders_video_array" class="slider" autoplay muted loop>
                        <source :src="`${slider}`">
                    </video>
                    <img style="border-radius: 3px" class="slider" v-for="(slider, index) in sliders_img_array"
                         :src="`${slider}`" :alt="`slider ${index}`">
                </div>
                <div class="switch-opacity">
                    <div class="switch"></div>
                </div>
            </div>

            <div id="about_studio" class="about-studio" :class="theme === 'light' ? 'p-light':'p-night'">
                <div class="title">
                    <h2>О студии</h2>
                </div>

                <div class="content">
                    <p>
                        Добро пожаловать в студию красоты KIMChill
                        — ваше идеальное место для преображения и ухода за собой! Мы предлагаем широкий спектр услуг:
                    </p>

                    <div class="cards-container">
                        <div class="cards">
                            <div class="card">
                                <img src="{{asset('img/cards-main/card 1.png')}}" alt="">
                                <p class="card-text"><span>Маникюр и педикюр:</span>
                                    Мы предлагаем как классические, так и современные техники,
                                    включая стильный дизайн ногтей. Позвольте вашим рукам и ногам сиять!</p>
                            </div>

                            <div class="card">
                                <img src="{{asset('img/cards-main/card 2.png')}}" alt="">
                                <p class="card-text"><span>Ламинирование и окрашивание бровей: </span>
                                    Наши специалисты помогут вам подобрать
                                    форму и цвет, чтобы создать гармоничный и выразительный образ.</p>
                            </div>

                            <div class="card">
                                <img src="{{asset('img/cards-main/card 3.png')}}" alt="">
                                <p class="card-text"><span>Ламинирование и наращивание ресниц: </span>
                                    Удлините и придайте объем вашим ресницам с помощью наших профессиональных услуг.</p>
                            </div>

                            <div class="card">
                                <img src="{{asset('img/cards-main/card 4.png')}}" alt="">
                                <p class="card-text"><span>Визаж и макияж: </span>
                                    Нужен идеальный образ для особого случая? Наши визажисты создадут для вас макияж,
                                    который будет идеально соответствовать вашему стилю.
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div id="reviews" class="reviews-block" :class="theme === 'light' ? 'p-light':'p-night'">
                <div class="title">
                    <h2>Отзывы</h2>
                </div>

                <div class="reviews">
                    <div v-for="(review, review_index) in reviews" v-if="reviews.length > 0">
                        <div class="review" v-if="review_index < 3 || reviews_open_short === true">
                            <div class="review-header">
                                <div class="profile">
                                    <div class="profile-img">
                                        <img :src="`${review.user.img}`" alt="user" v-if="review.user.img != ''">
                                        <svg v-else viewBox="0 0 304 304" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M214.249 120.277C214.249 152.83 186.16 179.717 150.88 179.717C115.599 179.717 87.5107 152.83 87.5107 120.277C87.5107 87.7231 115.599 60.8359 150.88 60.8359C186.16 60.8359 214.249 87.7231 214.249 120.277Z" :stroke="theme === 'light' ? 'black':'white'" stroke-width="10"/>
                                            <path d="M264.83 294.265C264.83 282.274 262.388 270.399 257.643 259.321C252.898 248.242 245.944 238.176 237.177 229.696C228.41 221.217 218.002 214.491 206.547 209.902C195.093 205.313 182.815 202.951 170.417 202.951" :stroke="theme === 'light' ? 'black':'white'" stroke-width="10"/>
                                            <path d="M39.292 294.265C39.292 282.274 41.8066 270.399 46.6921 259.321C51.5777 248.242 58.7386 238.176 67.766 229.696C76.7933 221.217 87.5104 214.491 99.3052 209.902C111.1 205.313 123.742 202.951 136.508 202.951H170.421" :stroke="theme === 'light' ? 'black':'white'" stroke-width="10"/>
                                            <rect x="5" y="5" width="294" height="294" rx="15" :stroke="theme === 'light' ? 'black':'white'" stroke-width="10"/>
                                        </svg>
                                    </div>

                                    <div class="profile-info">
                                        <p class="name">@{{ review.user.name }} @{{ review.user.surname }}</p>
                                        <p class="date-added" :class="theme === 'light' ? 'gray-p-light':'gray-p-night'">
                                            @{{ date_format(review.created_at) }}</p>
                                    </div>
                                </div>
                                <div class="stars">
                                    <div class="star" v-for="star in Number(review.stars)">
                                        <svg width="25" height="24" viewBox="0 0 34 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M13.9282 1.95194C15.5213 -0.313193 18.8787 -0.313193 20.4718 1.95194L23.632 6.4451C24.1421 7.17043 24.8788 7.70568 25.7263 7.96671L30.9761 9.58375C33.6227 10.3989 34.6601 13.592 32.9982 15.8071L29.7015 20.2011C29.1693 20.9104 28.8879 21.7764 28.9015 22.6631L28.9859 28.1556C29.0284 30.9246 26.3123 32.898 23.692 32.0019L18.4944 30.2243C17.6553 29.9374 16.7447 29.9374 15.9056 30.2243L10.708 32.0019C8.08771 32.898 5.37156 30.9246 5.4141 28.1556L5.49849 22.6631C5.51211 21.7764 5.23072 20.9104 4.69853 20.2011L1.40182 15.8071C-0.260139 13.592 0.777339 10.3989 3.42391 9.58375L8.67371 7.96671C9.52118 7.70568 10.2579 7.17043 10.768 6.4451L13.9282 1.95194Z" fill="#BC13FE"/>
                                        </svg>
                                    </div>

                                    <div class="star" v-for="star in (5 - review.stars)">
                                        <svg width="25" height="24" viewBox="0 0 34 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M13.9282 1.95194C15.5213 -0.313193 18.8787 -0.313193 20.4718 1.95194L23.632 6.4451C24.1421 7.17043 24.8788 7.70568 25.7263 7.96671L30.9761 9.58375C33.6227 10.3989 34.6601 13.592 32.9982 15.8071L29.7015 20.2011C29.1693 20.9104 28.8879 21.7764 28.9015 22.6631L28.9859 28.1556C29.0284 30.9246 26.3123 32.898 23.692 32.0019L18.4944 30.2243C17.6553 29.9374 16.7447 29.9374 15.9056 30.2243L10.708 32.0019C8.08771 32.898 5.37156 30.9246 5.4141 28.1556L5.49849 22.6631C5.51211 21.7764 5.23072 20.9104 4.69853 20.2011L1.40182 15.8071C-0.260139 13.592 0.777339 10.3989 3.42391 9.58375L8.67371 7.96671C9.52118 7.70568 10.2579 7.17043 10.768 6.4451L13.9282 1.95194Z" fill="#808080"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div class="content-review">
                                <div style="padding-bottom: 1rem">
                                    <p v-if="review.open_short === false">@{{ review.text_short }}
                                        <button class="pagination-btn"
                                                v-if="review.description.trim().split(/\s+/).length > 20"
                                                @click="toggle_pagination(review_index)">ещё...</button>
                                    </p>
                                    <p v-else>@{{ review.description }}
                                        <button class="pagination-btn"
                                                v-if="review.description.trim().split(/\s+/).length > 20"
                                                @click="toggle_pagination(review_index)">свернуть...</button>
                                    </p>

                                </div>

                                <div class="container-review-item" v-if="review.imgs != ''">
                                    <div class="review-imgs">
                                        <div class="review-imgs-container" v-for="(img, index) in review.imgs.split(';')">
                                            <img :src="`${img}`" alt="" :class="getImageClass(index, review.imgs.split(';').length)">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="button-end" v-if="reviews.length > 3">
                        <button class="full-btn" @click="reviews_pagination"
                                v-if="reviews_open_short === false && reviews.length > 3">ЕЩЁ...</button>
                        <button class="full-btn" @click="reviews_pagination"
                                v-if="reviews_open_short === true && reviews.length > 3">Свернуть</button>
                    </div>

                    <div v-if="reviews.length == 0">
                        <p style="text-align: center">Пока не добавлено ни одного отзыва</p>
                    </div>
                </div>

                <div class="review-form">
                    <div class="sub-title">
                        <h3>Оставить отзыв</h3>
                    </div>

                    <div :class="message_store_review ? 'alert-success':''">
                        @{{ message_store_review }}
                    </div>

                    <div :class="message_edit_review ? 'alert-success':''">
                        @{{ message_edit_review }}
                    </div>

                    @if(\Illuminate\Support\Facades\Auth::user())
                        <form id="StoreReviewForm" @submit.prevent="storeReview" v-if="review == null">

                            <div class="stars stars-form">
                                <button type="button" class="star" v-for="star in 5" @click="star_button(star)">
                                    <svg width="40" height="39" viewBox="0 0 34 33" fill="none" xmlns="http://www.w3.org/2000/svg" v-if="star <= stars">
                                        <path d="M13.9282 1.95194C15.5213 -0.313193 18.8787 -0.313193 20.4718 1.95194L23.632 6.4451C24.1421 7.17043 24.8788 7.70568 25.7263 7.96671L30.9761 9.58375C33.6227 10.3989 34.6601 13.592 32.9982 15.8071L29.7015 20.2011C29.1693 20.9104 28.8879 21.7764 28.9015 22.6631L28.9859 28.1556C29.0284 30.9246 26.3123 32.898 23.692 32.0019L18.4944 30.2243C17.6553 29.9374 16.7447 29.9374 15.9056 30.2243L10.708 32.0019C8.08771 32.898 5.37156 30.9246 5.4141 28.1556L5.49849 22.6631C5.51211 21.7764 5.23072 20.9104 4.69853 20.2011L1.40182 15.8071C-0.260139 13.592 0.777339 10.3989 3.42391 9.58375L8.67371 7.96671C9.52118 7.70568 10.2579 7.17043 10.768 6.4451L13.9282 1.95194Z" fill="#BC13FE"/>
                                    </svg>
                                    <svg width="40" height="39" viewBox="0 0 34 33" fill="none" xmlns="http://www.w3.org/2000/svg" v-if="star > stars">
                                        <path d="M13.9282 1.95194C15.5213 -0.313193 18.8787 -0.313193 20.4718 1.95194L23.632 6.4451C24.1421 7.17043 24.8788 7.70568 25.7263 7.96671L30.9761 9.58375C33.6227 10.3989 34.6601 13.592 32.9982 15.8071L29.7015 20.2011C29.1693 20.9104 28.8879 21.7764 28.9015 22.6631L28.9859 28.1556C29.0284 30.9246 26.3123 32.898 23.692 32.0019L18.4944 30.2243C17.6553 29.9374 16.7447 29.9374 15.9056 30.2243L10.708 32.0019C8.08771 32.898 5.37156 30.9246 5.4141 28.1556L5.49849 22.6631C5.51211 21.7764 5.23072 20.9104 4.69853 20.2011L1.40182 15.8071C-0.260139 13.592 0.777339 10.3989 3.42391 9.58375L8.67371 7.96671C9.52118 7.70568 10.2579 7.17043 10.768 6.4451L13.9282 1.95194Z" fill="#808080"/>
                                    </svg>
                                </button>
                            </div>

                            <div class="mb-1">
                                <textarea rows="6" class="form-settings textarea-settings" placeholder="Напишите о своих вречатлениях"
                                          :class="{
                                              'p-light': theme === 'light',
                                              'p-night': theme !== 'light',
                                              'is-invalid': errors.review_text
                                              }"
                                          name="review_text" id="review_text"></textarea>

                                <div class="invalid-feedback" v-for="error in errors.review_text">
                                    @{{ error }}
                                </div>
                            </div>

                            <div class="mb-1">
                                <p style="margin-bottom: 0.5rem">Можно прикрепить максимум 5 фото</p>
                                <div class="form-settings" id="img-review-input" :class="errors.img_review ? 'is-invalid':''">
                                    <input type="file" name="img_review[]" id="img_review" hidden multiple
                                           @change="checkColImg('img_review')">
                                    <label for="img_review" class="custom-file-upload">
                                        Загрузить файл
                                    </label>
                                </div>

                                <div class="invalid-feedback" v-for="error in errors.img_review">
                                    @{{ error }}
                                </div>

                                <div class="invalid-feedback" v-if="error_img_col === true">
                                    Нельзя добавить больше 5 фото
                                </div>
                            </div>

                            <button class="btn-full-form">Отправить</button>
                        </form>

                        <form id="EditReviewForm" @submit.prevent="editReview" v-if="review != null">

                            <div class="stars stars-form">
                                <button type="button" class="star" v-for="star in 5" @click="star_button_edit(star)">
                                    <svg width="40" height="39" viewBox="0 0 34 33" fill="none" xmlns="http://www.w3.org/2000/svg" v-if="star <= review_obj.stars">
                                        <path d="M13.9282 1.95194C15.5213 -0.313193 18.8787 -0.313193 20.4718 1.95194L23.632 6.4451C24.1421 7.17043 24.8788 7.70568 25.7263 7.96671L30.9761 9.58375C33.6227 10.3989 34.6601 13.592 32.9982 15.8071L29.7015 20.2011C29.1693 20.9104 28.8879 21.7764 28.9015 22.6631L28.9859 28.1556C29.0284 30.9246 26.3123 32.898 23.692 32.0019L18.4944 30.2243C17.6553 29.9374 16.7447 29.9374 15.9056 30.2243L10.708 32.0019C8.08771 32.898 5.37156 30.9246 5.4141 28.1556L5.49849 22.6631C5.51211 21.7764 5.23072 20.9104 4.69853 20.2011L1.40182 15.8071C-0.260139 13.592 0.777339 10.3989 3.42391 9.58375L8.67371 7.96671C9.52118 7.70568 10.2579 7.17043 10.768 6.4451L13.9282 1.95194Z" fill="#BC13FE"/>
                                    </svg>
                                    <svg width="40" height="39" viewBox="0 0 34 33" fill="none" xmlns="http://www.w3.org/2000/svg" v-if="star > review_obj.stars">
                                        <path d="M13.9282 1.95194C15.5213 -0.313193 18.8787 -0.313193 20.4718 1.95194L23.632 6.4451C24.1421 7.17043 24.8788 7.70568 25.7263 7.96671L30.9761 9.58375C33.6227 10.3989 34.6601 13.592 32.9982 15.8071L29.7015 20.2011C29.1693 20.9104 28.8879 21.7764 28.9015 22.6631L28.9859 28.1556C29.0284 30.9246 26.3123 32.898 23.692 32.0019L18.4944 30.2243C17.6553 29.9374 16.7447 29.9374 15.9056 30.2243L10.708 32.0019C8.08771 32.898 5.37156 30.9246 5.4141 28.1556L5.49849 22.6631C5.51211 21.7764 5.23072 20.9104 4.69853 20.2011L1.40182 15.8071C-0.260139 13.592 0.777339 10.3989 3.42391 9.58375L8.67371 7.96671C9.52118 7.70568 10.2579 7.17043 10.768 6.4451L13.9282 1.95194Z" fill="#808080"/>
                                    </svg>
                                </button>
                            </div>

                            <div class="mb-1">
                                <textarea rows="6" class="form-settings textarea-settings" v-model="review_obj.text"
                                          placeholder="Напишите о своих вречатлениях"
                                          :class="{
                                              'p-light': theme === 'light',
                                              'p-night': theme !== 'light',
                                              'is-invalid': errors.review_text_edit
                                            }"
                                           name="review_text_edit" id="review_text_edit">
                                </textarea>

                                <div class="invalid-feedback" v-for="error in errors.review_text_edit">
                                    @{{ error }}
                                </div>
                            </div>

                            <div class="mb-1">
                                <p style="margin-bottom: 0.5rem">Можно прикрепить максимум 5 фото</p>
                                <div class="form-settings" id="img-review-input" :class="errors.img_new ? 'is-invalid':''">
                                    <input type="file" name="img_new[]" id="img_new" hidden multiple
                                           @change="checkColImg('img_new')">
                                    <label for="img_new" class="custom-file-upload">
                                        Загрузить файл
                                    </label>
                                </div>

                                <div class="invalid-feedback" v-for="error in errors.img_new">
                                    @{{ error }}
                                </div>
                                <div class="invalid-feedback" v-if="error_img_col === true">
                                    Нельзя добавить больше 5 фото
                                </div>
                            </div>

                            <div class="container-review-item" v-if="review.imgs != []">
                                <div class="review-imgs mb-03">
                                    <div class="review-imgs-container" v-for="(img, index) in review_obj.imgs">
                                        <div class="opacity-review" :class="getImageClass(index, review_obj.imgs.length)">
                                            <button type="button" class="btn-danger-form" @click="delete_img_review_modal(index)">Удалить</button>
                                        </div>
                                        <img :src="`${img}`" alt="" :class="getImageClass(index, review_obj.imgs.length)">
                                    </div>
                                </div>
                            </div>



                            <button class="btn-full-form">Отправить</button>
                        </form>
                    @endif

                    @if(!\Illuminate\Support\Facades\Auth::user())
                        <p>Чтобы оставить отзыв, <a href="{{route('authorization')}}" class="link-a">авторизуйтесь.</a></p>
                    @endif

                </div>
            </div>

            <div id="address" class="map-block">
                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2212.75139671272!2d44.060348415727816!3d56.31686362409032!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4151d4f2c0afd487%3A0x65c365c7390c730f!2z0YPQuy4g0KDQvtC00LjQvtC90L7QstCwLCAyM9CQLCDQndC40LbQvdC40Lkg0J3QvtCy0LPQvtGA0L7QtCwg0J3QuNC20LXQs9C-0YDQvtC00YHQutCw0Y8g0L7QsdC7LiwgNjAzMDkz!5e0!3m2!1sru!2sru!4v1742838246637!5m2!1sru!2sru"
                            width="100%" height="500" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>

        </div>
    </div>
    @include('layout.footer')

    <script>

        const App = {
            data() {
                return {
                    message_store_review: '',
                    message_edit_review: '',
                    errors: [],
                    reviews: [],
                    reviews_open_short: false,
                    review: null,
                    user: '',
                    stars: 1,
                    review_obj: {
                        id: 0,
                        stars: 1,
                        text: '',
                        imgs: [],
                    },

                    description: '',

                    sliders_img_array: [
                        'img/sliders/slider 1.png',
                        'img/sliders/slider 2.png',
                        'img/sliders/slider 3.png',
                        'img/sliders/slider 4.png',
                        'img/sliders/slider 5.png',
                    ],

                    sliders_video_array: [
                        'img/sliders/videoslider1.MOV',
                        'img/sliders/videoslider2.MOV',
                        'img/sliders/videoslider3.MOV',
                        'img/sliders/videoslider4.MOV',
                        'img/sliders/videoslider5.MOV',
                    ],

                    theme: localStorage.getItem('theme') || 'light',
                    error_img_col: false

                }
            },

            methods: {
                async getReviews() {
                    const response = await fetch('{{route('getReviews')}}');
                    this.reviews = await response.json();
                    for(let i = 0; i < this.reviews.length; i++) {
                        this.reviews[i].text_short = this.get_pagination_text(this.reviews[i].description);
                        this.reviews[i].open_short = false;
                    }
                },
                async getUser() {
                    const response = await fetch('{{route('getUser')}}');
                    this.user = await response.json();
                    this.review = this.user.review[0];
                    if (this.review != null) {
                        this.review_obj.id = this.review.id;
                        this.review_obj.stars = this.review.stars;
                        this.review_obj.text = this.review.description;
                        if (this.review.imgs.length !== 0) this.review_obj.imgs = this.review.imgs.split(';');
                    }
                },

                //store-methods
                async storeReview() {
                    let form = document.getElementById('StoreReviewForm');
                    let data = new FormData(form);
                    data.append('stars', this.stars);
                    const response = await fetch('{{route('storeReview')}}', {
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        },
                        body:data
                    });
                    if(response.status === 201) {
                        this.message_store_review = await response.json();
                        this.errors = [];
                        form.reset();
                        document.getElementById('img-review-input').classList.remove('is-invalid');
                        this.error_img_col = false;
                        this.getReviews();
                        this.getUser();
                    }
                    if(response.status === 400) {
                        this.errors = await response.json();
                        this.message_store_review = '';
                    }
                },

                //edit-methods
                async editReview() {
                    let form = document.getElementById('EditReviewForm');
                    let data = new FormData(form);
                    data.append('id', this.review_obj.id);
                    data.append('stars', this.review_obj.stars);
                    data.append('imgs', this.review_obj.imgs);
                    const response = await fetch('{{route('editReview')}}', {
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        },
                        body:data
                    });
                    if(response.status === 200) {
                        this.message_edit_review = await response.json();
                        this.message_store_review = '';
                        this.errors = [];
                        document.getElementById('img_new').value = '';
                        document.querySelector('.custom-file-upload').textContent = 'Загрузить файл';
                        this.getReviews();
                        this.getUser();
                    }
                    if(response.status === 400) {
                        this.errors = await response.json();
                        this.message_edit_review = '';
                    }
                },

                star_button(index) {
                    this.stars = index;
                },
                star_button_edit(index) {
                    this.review_obj.stars = index;
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

                delete_img_review_modal(index) {
                    this.review_obj.imgs.splice(index, 1);
                },
                getImageClass(index, totalImages) {
                    if (totalImages === 1) return 'alone-img';
                    if (index === 0) return 'first-img';
                    if (index + 1 === totalImages) return 'last-img';
                    return 'center-img';
                },

                //pagination
                reviews_pagination() {
                    if (this.reviews_open_short === false) this.reviews_open_short = true;
                    else this.reviews_open_short = false;
                },
                get_pagination_text(text) {
                    if (!text || typeof text !== 'string') return '';
                    let words = text.trim().split(/\s+/).filter(word => word.length > 0);
                    if (words.length <= 20) return text;
                    return words.slice(0, 20).join(' ');
                },
                toggle_pagination(review_index) {
                    if (this.reviews[review_index].open_short === false) this.reviews[review_index].open_short = true;
                    else this.reviews[review_index].open_short = false;
                },

                customImgInput(id) {
                    let imgInput = document.getElementById(id);
                    let arrayImgs = imgInput.files;
                    const imgNames = Array.from(arrayImgs).map(file => file.name).join(', ');
                    document.querySelector('.custom-file-upload').textContent = imgNames || 'Загрузить файл';
                },
                checkColImg(id) {
                    let imgInput = document.getElementById(id);
                    if (this.review_obj.imgs.length + imgInput.files.length > 5) {
                        document.getElementById('img-review-input').classList.add('is-invalid');
                        this.error_img_col = true;
                        imgInput.value = '';
                    } else {
                        document.getElementById('img-review-input').classList.remove('is-invalid');
                        this.error_img_col = false;
                        this.customImgInput(id);
                    }
                }

            },

            created() {
                window.addEventListener('theme-changed', (event) => {
                    this.theme = event.detail;
                });
            },

            mounted() {
                this.getReviews();
                @if(\Illuminate\Support\Facades\Auth::user())
                    this.getUser();
                @endif
            },

        }
        Vue.createApp(App).mount('#Welcome');
    </script>

    <script>
        let currentIndex = 0;
        const sliders = document.querySelector('.sliders');
        const indicator_width = document.querySelector('.switch-opacity').offsetWidth;
        const switch_indicator = document.querySelector('.switch');

        if (document.querySelector('.container').offsetWidth > 544 && document.querySelector('.container').offsetWidth <= 1920)  {

            setInterval(() => {
                currentIndex = (currentIndex + 2) % 8;
                sliders.style.transform = `translateX(-${currentIndex * (100 / 4)}%)`;

                switch_indicator.style.transform = `translateX(${currentIndex * (100 / 2)}%)`;
            }, 10000);
            document.addEventListener('click', getCoordsRelativeToElement);
        }

        if (document.querySelector('.container').offsetWidth <= 544)  {

            setInterval(() => {
                currentIndex++;
                if (currentIndex === 5) {
                    currentIndex = 0;
                }
                sliders.style.transform = `translateX(-${currentIndex * 100}%)`;
                switch_indicator.style.transform = `translateX(${currentIndex * 100}%)`;
            }, 5000);


            document.addEventListener('click', getCoordsRelativeToElement);
        }

        function getCoordsRelativeToElement(event) {
            let indicator_part = 0;

            if (document.querySelector('.container').offsetWidth > 544 && document.querySelector('.container').offsetWidth <= 1920) {
                indicator_part = Math.floor(indicator_width / 4);
                if (event.offsetX > 0 && event.offsetX <= indicator_part) {
                    currentIndex = 0;
                    sliders.style.transform = `translateX(-0%)`;
                    switch_indicator.style.transform = `translateX(0%)`;
                }

                if (event.offsetX > indicator_part && event.offsetX <= indicator_part * 2) {
                    currentIndex = 2;
                    sliders.style.transform = `translateX(-50%)`;
                    switch_indicator.style.transform = `translateX(100%)`;
                }

                if (event.offsetX > indicator_part * 2 && event.offsetX <= indicator_part * 3) {
                    currentIndex = 4;
                    sliders.style.transform = `translateX(-100%)`;
                    switch_indicator.style.transform = `translateX(200%)`;
                }

                if (event.offsetX > indicator_part * 3 && event.offsetX <= indicator_part * 4) {
                    currentIndex = 6;
                    sliders.style.transform = `translateX(-150%)`;
                    switch_indicator.style.transform = `translateX(300%)`;
                }
            }

            if (document.querySelector('.container').offsetWidth <= 544) {
                indicator_part = Math.floor(indicator_width / 5);

                if (event.offsetX >= 0 && event.offsetX < indicator_part) {
                    currentIndex = 0;
                    sliders.style.transform = `translateX(-0%)`;
                    switch_indicator.style.transform = `translateX(0%)`;
                }

                if (event.offsetX >= indicator_part && event.offsetX < indicator_part * 2) {
                    currentIndex = 1;
                    sliders.style.transform = `translateX(-100%)`;
                    switch_indicator.style.transform = `translateX(100%)`;
                }

                if (event.offsetX >= indicator_part * 2 && event.offsetX < indicator_part * 3) {
                    currentIndex = 2;
                    sliders.style.transform = `translateX(-200%)`;
                    switch_indicator.style.transform = `translateX(200%)`;
                }

                if (event.offsetX >= indicator_part * 3 && event.offsetX < indicator_part * 4) {
                    currentIndex = 3;
                    sliders.style.transform = `translateX(-300%)`;
                    switch_indicator.style.transform = `translateX(300%)`;
                }

                if (event.offsetX >= indicator_part * 4 && event.offsetX < indicator_part * 5) {
                    currentIndex = 4;
                    sliders.style.transform = `translateX(-400%)`;
                    switch_indicator.style.transform = `translateX(400%)`;
                }

                if (event.offsetX >= indicator_part * 5 && event.offsetX < indicator_part * 6) {
                    currentIndex = 5;
                    sliders.style.transform = `translateX(-500%)`;
                    switch_indicator.style.transform = `translateX(500%)`;
                }
                //
                // if (event.offsetX >= indicator_part * 6 && event.offsetX < indicator_part * 7) {
                //     currentIndex = 6;
                //     sliders.style.transform = `translateX(-600%)`;
                //     switch_indicator.style.transform = `translateX(600%)`;
                // }
                //
                // if (event.offsetX >= indicator_part * 7 && event.offsetX < indicator_part * 8) {
                //     currentIndex = 7;
                //     sliders.style.transform = `translateX(-700%)`;
                //     switch_indicator.style.transform = `translateX(700%)`;
                // }
                //
                // if (event.offsetX >= indicator_part * 8 && event.offsetX < indicator_part * 9) {
                //     currentIndex = 8;
                //     sliders.style.transform = `translateX(-800%)`;
                //     switch_indicator.style.transform = `translateX(800%)`;
                // }
                //
                // if (event.offsetX >= indicator_part * 9 && event.offsetX < indicator_part * 10) {
                //     currentIndex = 9;
                //     sliders.style.transform = `translateX(-900%)`;
                //     switch_indicator.style.transform = `translateX(900%)`;
                // }

            }

        }
    </script>

@endsection
