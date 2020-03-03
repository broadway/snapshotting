broadway/snapshotting
=====================

Proof-of-concept component providing snapshotting functionality to [broadway/broadway](https://github.com/broadway/broadway).

[![Build Status](https://travis-ci.org/broadway/snapshotting.svg?branch=master)](https://travis-ci.org/broadway/snapshotting)

## Installation

```
$ composer require broadway/snapshotting
```

## Documentation
This project demonstrates taking and using snapshots of an aggregate root
in the EventSourcingRepository.

Snaphots can be triggered using different strategies like once every number of events or 
after an amount of time has passed. The `EventCountTrigger` implements the former strategy.

## Getting started
This repository is just a library. It lacks (for now) implementations of the `SnapshotRepository`.

For example if you want store your events and snapshots in a database using `doctrine/dbal`
you will have to:

* create a `DBALSnapshotRepository` implementing the `SnapshotRepository` to store and fetch 
 snapshots. You wil have to figure out how to serialize your aggregate root.

## License
This project is licensed under the MIT License - see the LICENSE file for details
