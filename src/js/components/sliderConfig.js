import Swiper from 'swiper/bundle';
import 'swiper/swiper-bundle.css';


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
		// scrollbar: {
		// 	el: '.swiper-scrollbar',
		// },
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

	contacts: {
		slidesPerView: 1,
		loop: true,
		pagination: {
			el: '.swiper-pagination',
			clickable: true
		},
		scrollbar: {
			el: '.swiper-scrollbar',
		}
	},

	cardItemSidebar: {
		direction: 'vertical',
		spaceBetween: 10,
		slidesPerView: 4,
		loop: true,
		watchSlidesVisibility: true,
		watchSlidesProgress: true,
		breakpoints: {
			200: {
				direction: 'horizontal',
				slidesPerView: 3,
			},
			1201: {
				direction: 'vertical',
				spaceBetween: 10,
				slidesPerView: 4,
			}
		}
	},

	cardItemMain: {
		slidesPerView: 1,
		loop: true,
		watchSlidesVisibility: true,
		watchSlidesProgress: true,
	},

	cardItemSeen: {
		spaceBetween: 20,
		slidesPerView: 2,
		loop: true,
		watchSlidesVisibility: true,
		watchSlidesProgress: true,
		pagination: {
			el: '.swiper-pagination',
			clickable: true
		},
		breakpoints: {
			200: {
				slidesPerView: 1,
			},
			1201: {
				slidesPerView: 2,
			}
		}
	},

}

export function getConfig(){
	return config
}

export function initSwipers() {
	const imgsSwiper = new Swiper('.image__slider', config.imgs);
	const tidIngSwiper = new Swiper('.tidings__slider', config.tidIngs);
	const contactsSwiper = new Swiper('.location__slider', config.contacts);
}

// export function initSwipersProduct(){
// 	const cardItemSidebarSwiper = new Swiper('.slider__sidebar', config.cardItemSidebar);
// 	config.cardItemMain.thumbs = { swiper: cardItemSidebarSwiper };
// 	const cardItemMainSwiper = new Swiper('.slider__main', config.cardItemMain);
// 	const cardItemSeenSwiper = new Swiper('.card__seen_slider', config.cardItemSeen);
// }