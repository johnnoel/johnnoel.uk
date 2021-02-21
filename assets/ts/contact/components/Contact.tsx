import React, { ReactElement, useState } from 'react';
import Message from './Message';

const Contact = (): ReactElement => {
    const [ show, setShow ] = useState(true);

    if (!show) {
        return <button type="button" onClick={() => setShow(true)}>Contact me</button>;
    }

    return <div className="contact">
        <div className="contact-row">
            <label htmlFor="js-contact-name">Name</label>
            <input type="text" id="js-contact-name" defaultValue="" />
        </div>

        <div className="contact-row">
            <label htmlFor="js-contact-email">Email address</label>
            <input type="email" id="js-contact-email" defaultValue="" />
        </div>

        <div className="contact-row">
            <label htmlFor="js-contact-message">Message</label>
            <Message id="js-contact-message" />
        </div>

        <button type="submit">Send message</button>
        <button type="button" onClick={() => setShow(false)}>Cancel</button>
    </div>;
};

export default Contact;
