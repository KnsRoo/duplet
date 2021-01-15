import Swiper, { Navigation, Pagination } from 'swiper';
import 'swiper/swiper-bundle.css';

Swiper.use([Navigation, Pagination]);

const config = {
	imgs: {
		direction: 'horizontal',
		loop: true,
		slidesPerView: 4,
		spaceBetween: 20,
		pagination: {
			el: '.swiper-pagination',
			clickable: true
		},
		scrollbar: {
			el: '.swiper-scrollbar',
		},
		breakpoints: {
			220: {
				slidesPerView: 1,
				spaceBetween: 20,
			},
			640: {
				slidesPerView: 2,
				spaceBetween: 20,
			},
			768: {
				slidesPerView: 2,
				spaceBetween: 40,
			},
			992: {
				slidesPerView: 3,
				spaceBetween: 20,
			},
			1200: {
				slidesPerView: 4,
				spaceBetween: 15,
			},
		}
	},
	tidIngs: {
		direction: 'horizontal',
		loop: true,
		slidesPerView: 1,
		loop: true,
		pagination: {
			el: '.swiper-pagination',
			clickable: true
		},
		scrollbar: {
			el: '.swiper-scrollbar',
		},
	},
	galleryThumbsOne: {
		spaceBetween: 10,
		slidesPerView: 4,
		loop: true,
		freeMode: true,
		loopedSlides: 5, //looped slides should be the same
		watchSlidesVisibility: true,
		watchSlidesProgress: true,
	},
	galleryTop: {
		spaceBetween: 10,
		loop: true,
		loopedSlides: 5, //looped slides should be the same
		navigation: {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		},
	},
	galleryThumbsTwo: {
		slidesPerView: 1,
		loop: true,
		pagination: {
			el: '.swiper-pagination',
			clickable: true
		},
	}
}

export default function initSwipers() {
	const imageSwiper = new Swiper('.image__slider', config.imgs),
		tidIngSwiper = new Swiper('.tidings__slider', config.tidIngs),
		galleryThumbsOneSwiper = new Swiper('.gallery-thumbs', config.galleryThumbsOne);
	config.galleryTop.thumbs = { swiper: galleryThumbsOneSwiper }
	const galleryTopSwiper = new Swiper('.gallery-top', config.galleryTop),
		galleryThumbsTwoSwiper = new Swiper('.location__slider', config.galleryThumbsTwo)
}