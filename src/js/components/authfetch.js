class AuthError extends Error {
    constructor(message) {
        super(message);
        this.name = "AuthError";
    }
}

export default async (url, params = {}, jwtKey = "jwt") => {
    const localStorageJSON = window.localStorage.getItem(jwtKey);
    if (!localStorageJSON)
        throw new AuthError("Вы не авторизованы");
    const localStorageJwt = JSON.parse(localStorageJSON);
    console.log(localStorageJwt);

        const apiLink = new URL(url);
        apiLink.searchParams.set(jwtKey, localStorageJwt.accessToken);

    return fetch(apiLink, params)
        .then((response) => {
            if (response.status === 409) {
                const refreshApiLink = new URL(localStorageJwt._links.refresher.href);
                refreshApiLink.searchParams.set("token", localStorageJwt.refreshToken);
                return fetch(refreshApiLink, { method: "POST" })
                    .then((refreshResponse) => {
                        if (refreshResponse.status === 200) {
                            return refreshResponse.json()
                                .then((data) => {
                                    window.localStorage.setItem(jwtKey, JSON.stringify(data));
                                    apiLink.searchParams.set(jwtKey, data.accessToken);

                                    return fetch(apiLink, params);
                                });
                        }
                        return refreshResponse;
                    });
            }
            return response;
        });
};
