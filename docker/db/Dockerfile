FROM postgres:15-alpine

# Set environment variables
ENV POSTGRES_DB=game_items \
    POSTGRES_USER=postgres \
    POSTGRES_PASSWORD=postgres

# Create app directory
RUN mkdir -p /app/database
WORKDIR /app/database