import '../scss/index.scss';

const contactContainer = document.getElementById('js-contact') as HTMLDivElement|null;
if (contactContainer !== null) {
    import(/* webpackChunkName: "contact" */'./contact/index').then(module => {
        module.default(contactContainer);
    });
}

const hcaptcha = document.querySelector('.h-captcha') as HTMLFormElement|null;
if (hcaptcha !== null) {
    import(/* webpackChunkName: "contact-lite" */'./hcaptcha/index').then(module => {
        module.default();
    });
}
