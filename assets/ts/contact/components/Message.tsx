import React, { ReactElement, useState } from 'react';
import ReactCommonmark from 'react-commonmark';
import classNames from 'classnames';
import debounce from 'lodash/debounce';

const Message = ({ id }: MessageProps): ReactElement => {
    const [ show, setShow ] = useState(Nav.Source);
    const [ message, setMessage ] = useState('');

    return <div className="message">
        <nav className="message-nav">
            <ul>
                <li className={classNames({ active: show === Nav.Source })}>
                    <button type="button" onClick={() => setShow(Nav.Source)}>Source</button>
                </li>
                <li className={classNames({ active: show === Nav.Preview })}>
                    <button type="button" onClick={() => setShow(Nav.Preview)}>Preview</button>
                </li>
            </ul>
        </nav>

        <div className={classNames('message-source', { show: show === Nav.Source })}>
            <textarea id={id} defaultValue={message} onChange={debounce(event => setMessage(event.target.value), 500)} />
            <p>Markdown is okay!</p>
        </div>

        <div className={classNames('message-preview', { show: show === Nav.Preview })}>
            <ReactCommonmark source={(message === '') ? '_No content_' : message} escapeHtml={true} />
        </div>
    </div>;
}

interface MessageProps {
    id: string;
}

enum Nav {
    Source = 'source',
    Preview = 'preview',
}

export default Message;
