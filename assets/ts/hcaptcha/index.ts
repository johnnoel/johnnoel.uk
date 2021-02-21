// called when hcaptcha submits successfully
(<any>window).hCaptchaCallback = (response: string) => {
    console.log(response);

    const field = document.getElementById('js-hcaptcha-field') as HTMLInputElement|null;
    if (field === null) {
        return;
    }

    field.value = response;
    field.closest('form')?.submit();
};

const attachHCaptcha = (): void => {};

export default attachHCaptcha;
