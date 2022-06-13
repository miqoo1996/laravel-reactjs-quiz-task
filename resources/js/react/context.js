import React, {createContext} from 'react';

export const contextData = {
    apiUrl: `${baseUrl}/api`,
    MODE_BINARY: 'binary',
    MODE_MULTIPLE: 'multiple_choice',
    MODES: {
        binary: 'Binary',
        multiple_choice: 'Multiple Choice',
    },
};

export const AppContext = createContext(contextData);
