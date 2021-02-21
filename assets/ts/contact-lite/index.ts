let widgetId: string|null = null;

// called when hcaptcha initialises
(<any>window).hCaptchaInit = () => {
    const container = document.getElementById('js-contact-lite-hcaptcha') as HTMLDivElement|null;

    if (container === null) {
        return;
    }

    widgetId = (<any>window).hcaptcha.render(container, {
        theme: 'dark',
        size: 'invisible',
        sitekey: '675456ac-95a5-48b0-b0ed-779cd9a14771',
    });
};

const attachHCaptcha = (formElement: HTMLFormElement): void => {
    const buttons = formElement.querySelectorAll('button[type="submit"]');
    if (buttons.length === 0) {
        return;
    }

    for (let i = 0; i < buttons.length; i++) {
        const button = buttons[i];
        button.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();

            if (widgetId === null) {
                return;
            }

            const ret = (<any>window).hcaptcha.execute(widgetId);

            // what happens now?
            console.log(ret);
        });
    }
};

export default attachHCaptcha;
