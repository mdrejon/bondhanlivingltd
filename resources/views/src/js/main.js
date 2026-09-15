import '../css/tailwind.css'
import '../scss/main.scss'

import $ from 'jquery'
import Swiper from 'swiper'
import { Autoplay, Pagination, EffectFade } from 'swiper/modules'
import 'swiper/css'
import 'swiper/css/effect-fade'
import 'swiper/css/pagination'

// ─── Hero Slider ────────────────────────────────────────────────────────────
const heroSwiper = new Swiper('.hero-swiper', {
  modules: [Autoplay, Pagination, EffectFade],
  loop: true,
  effect: 'fade',
  fadeEffect: { crossFade: true },
  speed: 1100,
  autoplay: {
    delay: 6000,
    disableOnInteraction: false,
  },
  pagination: {
    el: '.hero-pagination',
    clickable: true,
    bulletClass: 'swiper-pagination-bullet',
    bulletActiveClass: 'swiper-pagination-bullet-active',
  },
})

// ─── Testimonials Carousel ───────────────────────────────────────────────────
new Swiper('.testi-swiper', {
  modules: [Autoplay, Pagination, EffectFade],
  slidesPerView: 1,
  loop: true,
  effect: 'fade',
  fadeEffect: { crossFade: true },
  speed: 800,
  autoplay: { delay: 5000, disableOnInteraction: false },
  pagination: {
    el: '.testi-pagination',
    clickable: true,
    bulletClass: 'swiper-pagination-bullet',
    bulletActiveClass: 'swiper-pagination-bullet-active',
  },
})

// ─── Blog Carousel ───────────────────────────────────────────────────────────
new Swiper('.blog-swiper', {
  modules: [Pagination],
  slidesPerView: 3,
  spaceBetween: 28,
  loop: true,
  pagination: {
    el: '.blog-pagination',
    clickable: true,
    bulletClass: 'swiper-pagination-bullet',
    bulletActiveClass: 'swiper-pagination-bullet-active',
  },
  breakpoints: {
    0:    { slidesPerView: 1, spaceBetween: 18 },
    640:  { slidesPerView: 2, spaceBetween: 20 },
    1024: { slidesPerView: 3, spaceBetween: 28 },
  },
})

// ─── Mobile Menu Toggle ──────────────────────────────────────────────────────
$('#mobileMenuToggle').on('click', function () {
  const $menu = $('#mobileMenu')
  const isOpen = $menu.hasClass('open')

  if (isOpen) {
    $menu.removeClass('open')
    $(this).removeClass('active')
  } else {
    $menu.addClass('open')
    $(this).addClass('active')
  }
})

// Mobile sub-menu accordion
$('.mobile-item.has-sub > a').on('click', function (e) {
  e.preventDefault()
  const $sub = $(this).siblings('.mobile-sub')
  const $item = $(this).parent()

  if ($sub.hasClass('open')) {
    $sub.removeClass('open').slideUp(200)
    $item.removeClass('active')
  } else {
    $('.mobile-sub.open').removeClass('open').slideUp(200)
    $('.mobile-item.active').removeClass('active')
    $sub.addClass('open').slideDown(200)
    $item.addClass('active')
  }
})

// Close mobile menu when clicking outside
$(document).on('click', function (e) {
  if (!$(e.target).closest('.main-header').length) {
    $('#mobileMenu').removeClass('open')
    $('#mobileMenuToggle').removeClass('active')
  }
})

// ─── Sidebar Panel ───────────────────────────────────────────────────────────
function openSidebar() {
  $('#sidebarPanel').addClass('active')
  $('#sidebarOverlay').addClass('active')
  $('#sidebarToggle').addClass('active')
  $('body').css('overflow', 'hidden')
}

function closeSidebar() {
  $('#sidebarPanel').removeClass('active')
  $('#sidebarOverlay').removeClass('active')
  $('#sidebarToggle').removeClass('active')
  $('body').css('overflow', '')
}

$('#sidebarToggle').on('click', function () {
  if ($('#sidebarPanel').hasClass('active')) {
    closeSidebar()
  } else {
    openSidebar()
  }
})

$('#sidebarClose').on('click', closeSidebar)
$('#sidebarOverlay').on('click', closeSidebar)

$(document).on('keydown', function (e) {
  if (e.key === 'Escape') closeSidebar()
})

$('#sidebarQuoteForm').on('submit', function (e) {
  e.preventDefault()
  const $btn = $(this).find('.sidebar-submit')
  $btn.text('Sending...').prop('disabled', true)
  setTimeout(() => {
    $btn.text('Sent!').css('background', '#4CAF50')
    setTimeout(() => {
      $btn.text('Submit Now').css('background', '').prop('disabled', false)
      $(this)[0].reset()
    }, 2500)
  }, 1000)
})

// ─── Chat Widget ─────────────────────────────────────────────────────────────
$('#chatToggle').on('click', function () {
  $('#chatPopup').addClass('open')
})

$('#chatClose').on('click', function () {
  $('#chatPopup').removeClass('open')
})

// Close popup on overlay click (clicking outside popup area)
$(document).on('click', function (e) {
  const $popup = $('#chatPopup')
  const $toggle = $('#chatToggle')

  if (
    $popup.hasClass('open') &&
    !$(e.target).closest('#chatPopup').length &&
    !$(e.target).closest('#chatToggle').length
  ) {
    $popup.removeClass('open')
  }
})

// Prevent propagation inside popup
$('#chatPopup').on('click', function (e) {
  e.stopPropagation()
})

// Chat form submission
$('#chatForm').on('submit', function (e) {
  e.preventDefault()
  const $btn = $(this).find('.chat-submit')
  $btn.text('Sending...').prop('disabled', true)

  setTimeout(() => {
    $(this)[0].reset()
    $btn.text('Sent! ✓').css('background', '#3a9e6e')
    setTimeout(() => {
      $btn.text('Submit Now').prop('disabled', false).css('background', '')
      $('#chatPopup').removeClass('open')
    }, 2000)
  }, 1000)
})

// ─── Dropdown hover accessibility (keyboard) ────────────────────────────────
$('.nav-item.has-dropdown').on('mouseenter', function () {
  $(this).find('.dropdown-menu').stop(true, true).fadeIn(180)
}).on('mouseleave', function () {
  $(this).find('.dropdown-menu').stop(true, true).fadeOut(180)
})

// ─── Back To Top ─────────────────────────────────────────────────────────────
$(window).on('scroll', function () {
  if ($(this).scrollTop() > 400) {
    $('#backToTop').addClass('visible')
  } else {
    $('#backToTop').removeClass('visible')
  }
})

$('#backToTop').on('click', function (e) {
  e.preventDefault()
  $('html, body').animate({ scrollTop: 0 }, 600)
})

// ─── Sticky nav shadow on scroll ─────────────────────────────────────────────
$(window).on('scroll', function () {
  if ($(this).scrollTop() > 10) {
    $('.main-header').css('box-shadow', '0 4px 30px rgba(0,0,0,0.15)')
  } else {
    $('.main-header').css('box-shadow', '0 2px 20px rgba(0,0,0,0.08)')
  }
})

// ─── Hotel Gallery Lightbox ───────────────────────────────────────────────────
const lbImages = []
let lbCurrent = 0

// Collect all gallery images on page load
$(function () {
  $('.hg-item').each(function (i) {
    lbImages.push({ src: $(this).find('img').attr('src'), alt: $(this).find('img').attr('alt') || '' })
    $(this).attr('data-index', i)
  })
})

function lbOpen (index) {
  lbCurrent = (index + lbImages.length) % lbImages.length
  const { src, alt } = lbImages[lbCurrent]
  $('#lbImg').css('opacity', 0).attr({ src, alt }).on('load', function () {
    $(this).css('opacity', 1)
  })
  $('#lbCounter').text(`${lbCurrent + 1} / ${lbImages.length}`)
  $('#galleryLightbox').addClass('active')
  $('body').addClass('lb-open')
}

function lbClose () {
  $('#galleryLightbox').removeClass('active')
  $('body').removeClass('lb-open')
}

$('.hg-item').on('click', function () { lbOpen(parseInt($(this).attr('data-index'))) })
$('#lbClose').on('click', lbClose)
$('#lbPrev').on('click', function () { lbOpen(lbCurrent - 1) })
$('#lbNext').on('click', function () { lbOpen(lbCurrent + 1) })

// Close on backdrop click
$('#galleryLightbox').on('click', function (e) {
  if ($(e.target).is('#galleryLightbox') || $(e.target).is('.lb-stage')) lbClose()
})

// Keyboard navigation
$(document).on('keydown', function (e) {
  if (!$('#galleryLightbox').hasClass('active')) return
  if (e.key === 'Escape')      lbClose()
  if (e.key === 'ArrowLeft')   lbOpen(lbCurrent - 1)
  if (e.key === 'ArrowRight')  lbOpen(lbCurrent + 1)
})

// ─── FAQ Accordion ───────────────────────────────────────────────────────────
$('.faq-question').on('click', function () {
  const $item   = $(this).closest('.faq-item')
  const $answer = $item.find('.faq-answer')
  const isOpen  = $item.hasClass('active')

  // Close all
  $('.faq-item.active').not($item)
    .removeClass('active')
    .find('.faq-answer').slideUp(280)

  // Toggle clicked
  if (isOpen) {
    $item.removeClass('active')
    $answer.slideUp(280)
    $(this).attr('aria-expanded', 'false')
  } else {
    $item.addClass('active')
    $answer.slideDown(280)
    $(this).attr('aria-expanded', 'true')
  }
})

// Booking form
$('#bookingForm').on('submit', function (e) {
  e.preventDefault()
  const $btn = $(this).find('.btn-check-avail')
  $btn.text('Checking...').prop('disabled', true)
  setTimeout(() => {
    $btn.text('Available — Contact Us!').css('background', '#3a9e6e')
    setTimeout(() => {
      $btn.text('Check Availability').prop('disabled', false).css('background', '')
    }, 3000)
  }, 900)
})

// ─── About Section: Tab Switching ────────────────────────────────────────────
$('.about-tab-btn').on('click', function () {
  const tab = $(this).data('tab')

  $('.about-tab-btn').removeClass('active')
  $(this).addClass('active')

  $('.about-tab-pane').removeClass('active')
  $(`.about-tab-pane[data-tab="${tab}"]`).addClass('active')
})

// ─── About Section: Parallax Deco on Scroll ──────────────────────────────────
$(window).on('scroll', function () {
  const $section = $('.about-section')
  if (!$section.length) return

  const sectionTop = $section.offset().top
  const sectionH   = $section.outerHeight()
  const scrollY    = $(this).scrollTop()
  const viewH      = $(window).height()

  // Only animate while section is in viewport
  if (scrollY + viewH < sectionTop || scrollY > sectionTop + sectionH) return

  const progress = scrollY - sectionTop
  $('.about-deco-tl').css('transform', `translateY(${progress * 0.07}px)`)
  $('.about-deco-br').css('transform', `translateY(${progress * -0.05}px)`)
})

// ─── Newsletter form ─────────────────────────────────────────────────────────
$('.fn-form').on('submit', function (e) {
  e.preventDefault()
  const $btn = $(this).find('button')
  const $input = $(this).find('input')

  $btn.text('Subscribing...').prop('disabled', true)

  setTimeout(() => {
    $input.val('')
    $btn.text('Subscribed ✓')
    setTimeout(() => {
      $btn.text('Subscribe').prop('disabled', false)
    }, 3000)
  }, 800)
})

// ─── Check Availability Bar ───────────────────────────────────────────────────
$('#checkAvailForm').on('submit', function (e) {
  e.preventDefault()
  const $btn = $(this).find('.caf-btn')
  $(this).addClass('submitting')
  $btn.text('Checking...')

  setTimeout(() => {
    $(this).removeClass('submitting')
    $btn.text('Available!')
    setTimeout(() => $btn.text('Book Now'), 2800)
  }, 900)
})

// ─── About Page FAQ Accordion ────────────────────────────────────────────────
$('.afaq-question').on('click', function () {
  const $item   = $(this).closest('.afaq-item')
  const $answer = $item.find('.afaq-answer')
  const isOpen  = $item.hasClass('active')

  $('.afaq-item.active').not($item)
    .removeClass('active')
    .find('.afaq-answer').slideUp(280)

  if (isOpen) {
    $item.removeClass('active')
    $answer.slideUp(280)
  } else {
    $item.addClass('active')
    $answer.slideDown(280)
  }
})

// ─── Service Details – FAQ Accordion ─────────────────────────────────────────
$('.sd-faq-question').on('click', function () {
  const $item   = $(this).closest('.sd-faq-item')
  const $answer = $item.find('.sd-faq-answer')
  const isOpen  = $item.hasClass('active')

  $('.sd-faq-item.active').not($item)
    .removeClass('active')
    .find('.sd-faq-answer').slideUp(280)
  $('.sd-faq-item.active').not($item)
    .find('.sd-faq-question').attr('aria-expanded', 'false')

  if (isOpen) {
    $item.removeClass('active')
    $answer.slideUp(280)
    $(this).attr('aria-expanded', 'false')
  } else {
    $item.addClass('active')
    $answer.slideDown(280)
    $(this).attr('aria-expanded', 'true')
  }
})

// ─── Room Details – Thumbnail Switcher ───────────────────────────────────────
$('.rd-thumb').on('click', function () {
  const $img = $(this).find('img')
  $('#rdMainImg').attr({ src: $img.attr('src'), alt: $img.attr('alt') })
  $('.rd-thumb').removeClass('active')
  $(this).addClass('active')
})

// ─── Scroll Reveal (Intersection Observer) ────────────────────────────────────
const revealObserver = new IntersectionObserver(
  (entries) => {
    entries.forEach(({ target, isIntersecting }) => {
      if (isIntersecting) {
        target.classList.add('is-revealed')
      } else {
        // remove so element re-animates when scrolled back into view
        target.classList.remove('is-revealed')
      }
    })
  },
  {
    threshold: 0.12,
    rootMargin: '0px 0px -60px 0px',
  }
)

document.querySelectorAll('[data-reveal]').forEach((el) => revealObserver.observe(el))
