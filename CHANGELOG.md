# CHANGELOG

## v0.3.5 (2023-04-06)

### Fixed

- Fixed tests: update localstack region configuration.

## v0.3.4 (2022-12-23)

### Removed

- Remove serialize feature.

## v0.3.1 (2022-03-26)

### Added

- Support Laravel Queue UUID

### Fixed

- Bugfix on the PublisherFifo facade

## v0.2.7 (2021-05-2)

### Added

- Add FIFO support to SNS.
- Add FIFO support to SQS.

## v0.1.6 (2020-07-31)

### Fixed

- Remove default profile from SNS connector default configuration.

## v0.1.5 (2019-11-09)

### Added

- Publish messages through artisan command.

`php artisan bus:publish <message> <event_or_topicnNme>`

## v0.1.0 (2019-06-09)

### Initial Release
