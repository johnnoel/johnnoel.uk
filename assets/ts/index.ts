import '../scss/index.scss';

const contactContainer = document.getElementById('js-contact') as HTMLDivElement|null;
if (contactContainer !== null) {
    import(/* webpackChunkName: "contact" */'./contact/index').then(module => {
        module.default(contactContainer);
    });
}

const contactFormLite = document.getElementById('js-contact-lite') as HTMLFormElement|null;
if (contactFormLite !== null) {
    import(/* webpackChunkName: "contact-lite" */'./contact-lite/index').then(module => {
        module.default(contactFormLite);
    });
}
