# PhotoUploader

Welcome to the PhotoUploader! 

This webapp allows you to easily upload your photos (videos support is to come) to your self-hosted server.

## Features

- Upload photos: Easily upload your photos using the intuitive user interface.
- Organize the server-side folders: Choose exactly where you want to store your photos
- Set wrong or missing metadata : fix erroneous dates and set GPS data
- Fully secure authentication: using JWT tokens

For detailed documentation on how to use the PhotoUploader, please refer to the guide.

## Getting Started

This webapp uses a MySQL database, so it's made of multiple containers.
Thus, the standard installation is with docker-compose.

An example `docker-compose.prod.example.yml` is available in the `/docker` directory :

```yaml
version: "3.8"
services:
  photouploader-apache:
    container_name: photouploader-apache
    image: silloky/photouploader
    depends_on:
      - photouploader-db
    volumes:
      - /path/to/the/media/destination/folder/:/media:rw # CHANGE ME !
    ports:
      - <port>:80 # CHANGE ME !
    restart: unless-stopped
    environment: # KEEP THESE VALUES IN SYNC WITH BELOW !
      DB_HOST: photouploader-db
      DB_USER: php
      DB_PWD: photouploader
      DB_NAME: photouploader
      GEOAPIFY_KEY: <key> # Get yours at https://www.geoapify.com> ! It's free up to 3000 requests a day (more than enough)
  photouploader-db:
    container_name: photouploader-db
    image: mysql
    restart: unless-stopped
    environment:
      MYSQL_ROOT_PASSWORD: admin
      MYSQL_USER: php
      MYSQL_PASSWORD: photouploader
      MYSQL_DATABASE: photouploader
    ports:
      - "9906:3306"
    volumes:
      - /path/to/persistent/mysql/db/:/var/lib/mysql # CHANGE ME !
```

Make sure to edit these defaults values or it won't work.

## Building yourself

To build the webapp yourself, please do the following steps :

1. `git clone https://github.com/Silloky/PhotoUploader`
2. `cd PhotoUploader`
3. `docker build -t <yourusername> .`
4. `cp ./docker/docker-compose.prod.example.yml ./docker-compose.yml`
5. Customise the values in the `docker-compose.yml` to fit your needs
6. `docker-compose up -d`

If you edit the code in `/src` and want to see your edits without rebuilding, use the `dev` example instead of `prod`. Reload the page, and you'll see your edits !

## Contributing

I welcome contributions from the community! If you would like to contribute to PhotoUploader, please follow the (non-existent for the moment, sorry...)[Contribution Guidelines](./CONTRIBUTING.md).

## License

This project is licensed under the [MIT License](./LICENSE).
