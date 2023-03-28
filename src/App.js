import React from 'react';
import { BrowserRouter } from 'react-router-dom';
import UserTable from './components/UserTable';

const App = () => {
    return (
        <BrowserRouter>
            <UserTable data={dataArray} />
        </BrowserRouter>
    );
}

export default App; 