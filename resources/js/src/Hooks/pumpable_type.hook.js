import React from 'react'

export const usePumpableType = () => {
    const pumpableType = () => new URLSearchParams(window.location.search).get('pumpable_type')

    return pumpableType;
}
