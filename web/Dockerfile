FROM node:lts-alpine
WORKDIR /app
COPY package.json .
RUN npm prune --quiet
RUN npm install --quiet
COPY . .
CMD ["npm", "run", "serve"]
