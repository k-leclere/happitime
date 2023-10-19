FROM node:16-bookworm-slim

WORKDIR /data

ENV HOST=0.0.0.0
EXPOSE 3000


RUN chown -R node:node /data
USER node

COPY --chown=node:node ./package*.json /data
RUN --mount=type=cache,id=npm,target=/root/.npm npm i
COPY --chown=node:node . /data

ARG SUPABASE_URL
ARG SUPABASE_KEY

RUN npm run generate
# RUN npm prune --production

# default comand
CMD [ "npm", "run", "start" ]