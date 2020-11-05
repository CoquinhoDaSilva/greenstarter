const ratio = 0.5
const options = {
    root: null,
    rootMargin: '0px',
    threshold: ratio
}

const handleIntersect = function (entries, observer) {
    entries.forEach(function (entry) {
        if (entry.intersectionRatio > ratio) {
            entry.target.classList.add('reveal-visible')
            observer.unobserve(entry.target)
        }
    })
}

var observer = new IntersectionObserver(handleIntersect, options);
document.querySelectorAll('.reveal').forEach(function (r) {
    observer.observe(r)
})
document.querySelectorAll('.reveal-left').forEach(function (r) {
    observer.observe(r)
})
document.querySelectorAll('.reveal-right').forEach(function (r) {
    observer.observe(r)
})





const btnburger = document.querySelector('.btnburger');
const menuresp = document.querySelector('.menuresp');
const body = document.querySelector('.body');

btnburger.addEventListener('click', function () {
    menuresp.classList.toggle('active');
    body.classList.toggle('hidden');
});

menuresp.addEventListener('click', function () {
    menuresp.classList.toggle('active');
    body.classList.toggle('hidden');
})



const menuconnexion = document.querySelector('.connexion')
const btnconnexion = document.querySelector('.btnconnexion')
const menublog = document.querySelector('.menublog');
const btnmenublog = document.querySelector('.menublogposition');
const menuprojects = document.querySelector('.menuprojects');
const btnmenuprojects = document.querySelector('.menuprojectsposition');
const menuevent = document.querySelector('.menuevent');
const btnmenuevent = document.querySelector('.menueventposition');
const menusignal = document.querySelector('.menusignal');
const btnmenusignal = document.querySelector('.menusignalposition');


btnconnexion.addEventListener('click', function () {
    menuconnexion.classList.toggle('active');
    menublog.classList.remove('active');
    menuprojects.classList.remove('active');
    menuevent.classList.remove('active');
    menusignal.classList.remove('active');
})

btnmenublog.addEventListener('click', function () {
    menublog.classList.toggle('active');
    menuconnexion.classList.remove('active');
    menuprojects.classList.remove('active');
    menuevent.classList.remove('active');
    menusignal.classList.remove('active');
})

btnmenuprojects.addEventListener('click', function () {
    menuprojects.classList.toggle('active');
    menuconnexion.classList.remove('active');
    menublog.classList.remove('active');
    menuevent.classList.remove('active');
    menusignal.classList.remove('active');
})

btnmenuevent.addEventListener('click', function () {
    menuevent.classList.toggle('active');
    menuprojects.classList.remove('active');
    menuconnexion.classList.remove('active');
    menublog.classList.remove('active');
    menusignal.classList.remove('active');
})

btnmenusignal.addEventListener('click', function () {
    menusignal.classList.toggle('active');
    menuevent.classList.remove('active');
    menuprojects.classList.remove('active');
    menuconnexion.classList.remove('active');
    menublog.classList.remove('active');
})
