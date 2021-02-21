import React from 'react';
import { render } from 'react-dom';
import Contact from './components/Contact';

export default (container: HTMLDivElement): void => {
    render(<Contact />, container);
};
