FROM node:lts-alpine

# make the 'app' folder the current working directory
WORKDIR /var/www/html
# copy 'package.json' to install dependencies
COPY /app/package*.json /var/www/html

# install dependencies
RUN npm install

# copy files and folders to the current working directory (i.e. 'app' folder)
COPY ./app/ /var/www/html

RUN npm run dev
EXPOSE 8080
CMD [ "dist" ]