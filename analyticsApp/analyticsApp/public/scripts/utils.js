let randomColor = () => {
    let r = Math.floor(Math.random() * 255);
    let g = Math.floor(Math.random() * 255);
    let b = Math.floor(Math.random() * 255);
    return `rgb(${r},${g},${b})`
};

let getAllData = async (url = ``) => {
    const response = await fetch(url, {
        method: `GET`,
        mode: `cors`
    });
    return await response.json()
};